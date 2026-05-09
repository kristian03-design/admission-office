/* ─── SITE LOADER HIDE LOGIC ─── */
function hideSiteLoader() {
  const loader = document.getElementById('site-loader');
  if (!loader) return;
  loader.classList.add('is-hidden');
  document.body.classList.remove('site-loader-lock');
  setTimeout(() => loader.remove(), 550);
}
document.body.classList.add('site-loader-lock');
window.addEventListener('load', () => {
  setTimeout(hideSiteLoader, 350);
});
setTimeout(hideSiteLoader, 4500); // fallback

function showConfirmModal(options) {
  const { title, message, onConfirm, confirmText = "Confirm", cancelText = "Cancel", icon = "trash", danger = true } = options;
  const modal = document.getElementById('confirmModal');
  const titleEl = document.getElementById('confirmModalTitle');
  const msgEl = document.getElementById('confirmModalMessage');
  const confirmBtn = document.getElementById('confirmModalConfirmBtn');
  const cancelBtn = document.getElementById('confirmModalCancelBtn');
  const iconWrap = document.getElementById('confirmModalIcon');
  const iconInner = document.getElementById('confirmModalIconInner');

  if (!modal) {
    if (confirm(message.replace(/<[^>]*>?/gm, ''))) onConfirm();
    return;
  }

  titleEl.textContent = title;
  msgEl.innerHTML = message;
  confirmBtn.textContent = confirmText;
  cancelBtn.textContent = cancelText;
  
  if (danger) {
    confirmBtn.style.background = "#ef4444";
    iconWrap.style.background = "#fee2e2";
    iconWrap.style.color = "#ef4444";
  } else {
    confirmBtn.style.background = "var(--navy)";
    iconWrap.style.background = "var(--navy-pale)";
    iconWrap.style.color = "var(--navy)";
  }

  if (iconInner) {
    iconInner.setAttribute('data-iconsax', icon);
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }

  modal.style.display = 'flex';

  const cleanup = () => {
    modal.style.display = 'none';
    confirmBtn.onclick = null;
    cancelBtn.onclick = null;
  };

  confirmBtn.onclick = () => {
    cleanup();
    onConfirm();
  };

  cancelBtn.onclick = cleanup;
}

/* ─── DATA (from API only) ─── */
let API_PROGRAMS = [];
const programEnabled = {};
const pendingProgramSlots = {};
let isRefreshingData = false;
let programFilter = 'All';
let interviewFilter = 'All';


let currentPage = 'dashboard';
let appPage = 1;
const APP_PER_PAGE = 10;
let API_APPLICATIONS = null;
let filteredApps = [];
let SYSTEM_SETTINGS = null;
const ADMIN_LOGIN_URL = window.ADMIN_LOGIN_URL || '/admin/login';
const ADMIN_REFRESH_MS = 0;

function getApplications() {
  return Array.isArray(API_APPLICATIONS) ? API_APPLICATIONS : [];
}

function getPrograms() {
  return API_PROGRAMS;
}

function apiUrl(path) {
  const base = (typeof AdmissionAPI !== 'undefined' && AdmissionAPI.getBase)
    ? AdmissionAPI.getBase()
    : (window.ADMISSION_API_BASE || window.location.origin + '/api');
  const normalized = String(path || '').replace(/^\/api(?=\/|$)/, '');
  return base.replace(/\/$/, '') + (normalized.startsWith('/') ? normalized : '/' + normalized);
}

function apiFetch(path, options = {}) {
  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {}),
  };
  const token = typeof AdmissionAPI !== 'undefined' && AdmissionAPI.getToken
    ? AdmissionAPI.getToken()
    : sessionStorage.getItem('_at');
  if (token && !headers.Authorization) headers.Authorization = 'Bearer ' + token;
  return fetch(apiUrl(path), { ...options, headers });
}

function mapApiStatus(s) {
  if (!s) return 'Pending';
  const m = {
    pending: 'Pending',
    submitted: 'Pending',
    under_review: 'Pending',
    pending_docs: 'Pending',
    for_interview: 'Interview Scheduled',
    approved: 'Approved',
    accepted: 'Approved',
    rejected: 'Rejected',
    waitlisted: 'Pending',
    enrolled: 'Approved',
    cancelled: 'Rejected',
    draft: 'Pending'
  };
  return m[s] || 'Pending';
}

function mapStatusToApi(s) {
  const m = { 'Pending': 'under_review', 'Interview Scheduled': 'for_interview', 'Approved': 'accepted', 'Rejected': 'rejected' };
  return m[s] || 'under_review';
}

let trendChart, typeChart, programChart, gwaChart, eligChart;

/* ─── HELPERS ─── */
function avgGWA(app) {
  const g11 = app.g11 != null && app.g11 !== '' ? Number(app.g11) : null;
  const g12 = app.g12 != null && app.g12 !== '' ? Number(app.g12) : null;
  if ((g11 == null && g12 == null) || (g11 === 0 && g12 === 0)) return '—';
  const a = g11 != null ? g11 : g12;
  const b = g12 != null ? g12 : g11;
  return ((a + b) / 2).toFixed(1);
}

function statusClass(status) {
  return {
    'Pending': 'badge--pending',
    'Scheduled': 'badge--interview',
    'Interview Scheduled': 'badge--interview',
    'Completed': 'badge--approved',
    'No Show': 'badge--rejected',
    'Cancelled': 'badge--rejected',
    'Approved': 'badge--approved',
    'Rejected': 'badge--rejected',
  }[status] || 'badge--pending';
}

function normalizeInterviewStatus(s) {
  const v = String(s || '').trim().toLowerCase();
  if (!v) return 'Pending';
  if (v === 'pending') return 'Pending';
  if (v === 'scheduled' || v === 'interview scheduled' || v === 'for_interview') return 'Scheduled';
  if (v === 'completed' || v === 'done') return 'Completed';
  if (v === 'no show' || v === 'noshow' || v === 'no_show') return 'No Show';
  if (v === 'cancelled' || v === 'canceled') return 'Cancelled';
  // Fallback: Title Case-ish
  return (s && typeof s === 'string') ? s : 'Pending';
}

function initials(app) { return ((app.firstName || '')[0] + (app.surname || '')[0]).toUpperCase() || '—'; }

/** Full name as "LastName, FirstName MiddleName" */
function fullNameDisplay(app) {
  const last = (app.surname || '').trim();
  const first = (app.firstName || '').trim();
  const middle = (app.middleName || '').trim();
  const firstMiddle = [first, middle].filter(Boolean).join(' ');
  return last ? (firstMiddle ? `${last}, ${firstMiddle}` : last) : (firstMiddle || '—');
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-PH', { month: 'short', day: 'numeric', year: 'numeric' });
}

function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

function afterToastPaint(callback) {
  requestAnimationFrame(() => {
    setTimeout(callback, 0);
  });
}

function refreshDataSoon() {
  afterToastPaint(() => refreshData(false));
}

function reloadContentSoon(loader) {
  afterToastPaint(loader);
}

function formatDeadline(dateStr) {
  if (!dateStr || String(dateStr).trim().toUpperCase() === 'TBA') return 'TBA';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return 'TBA';
  return d.toLocaleDateString('en-PH', { month: 'long', day: 'numeric', year: 'numeric' });
}

function setDeadlineFieldValue(value) {
  const el = document.getElementById('settingDeadline');
  if (!el) return;

  const deadline = String(value || '').trim();
  if (/^\d{4}-\d{2}-\d{2}$/.test(deadline)) {
    el.type = 'date';
    el.value = deadline;
    return;
  }

  el.type = 'text';
  el.value = 'TBA';
}

function getDeadlineFieldValue() {
  const el = document.getElementById('settingDeadline');
  if (!el) return '';

  const value = String(el.value || '').trim();
  return value.toUpperCase() === 'TBA' ? '' : value;
}

function initDeadlineField() {
  const el = document.getElementById('settingDeadline');
  if (!el) return;

  el.addEventListener('focus', () => {
    if (el.type === 'text' && String(el.value || '').trim().toUpperCase() === 'TBA') {
      el.type = 'date';
      el.value = '';
      if (typeof el.showPicker === 'function') el.showPicker();
    }
  });

  el.addEventListener('blur', () => {
    if (!String(el.value || '').trim()) setDeadlineFieldValue('');
  });
}

function applySystemSettingsUI(settings) {
  if (!settings || typeof settings !== 'object') return;
  SYSTEM_SETTINGS = settings;

  // Topbar school year
  const topbarSY = document.getElementById('topbarSY');
  if (topbarSY && settings.school_year) topbarSY.textContent = settings.school_year;

  // Dashboard deadline chip
  const deadlineChip = document.querySelector('.deadline-chip');
  if (deadlineChip) {
    const deadlineText = formatDeadline(settings.application_deadline);
    if (deadlineText) deadlineChip.innerHTML = `<i data-iconsax="clock" style="width:13px;height:13px"></i> Deadline: ${deadlineText}`;
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }
}

function escapeHtml(str) {
  if (str == null) return '';
  const s = String(str);
  const div = document.createElement('div');
  div.textContent = s;
  return div.innerHTML;
}

function storageUrl(path) {
  const value = String(path || '').trim();
  if (!value) return '';
  const supabaseS3Match = value.match(/^(https?:\/\/[^/]+)?\/storage\/v1\/s3\/([^/]+)\/(.+)$/i);
  if (supabaseS3Match) {
    const origin = supabaseS3Match[1] || '';
    const bucket = supabaseS3Match[2];
    const key = supabaseS3Match[3].replace(/^\/+/, '');
    return `${origin}/storage/v1/object/public/${bucket}/${key}`;
  }
  if (/^(https?:|data:|blob:)/i.test(value)) return value;

  const clean = value.replace(/^\/+/, '').replace(/^storage\//, '');
  return '/uploaded-storage/' + clean;
}

function shortProg(name) {
  if (!name) return '';
  const n = String(name).toLowerCase();

  if (n.includes('information technology')) return 'BSIT';
  if (n.includes('accounting information system')) return 'BSAIS';
  if (n.includes('internal auditing')) return 'BSIA';
  if (n.includes('management accounting')) return 'BSMA';
  if (n.includes('accountancy')) return 'BSA';
  if (n.includes('hospitality management')) return 'BSHM';
  if (n.includes('tourism management')) return 'BSTM';
  if (n.includes('elementary education')) return 'BEED';
  if (n.includes('secondary education') && n.includes('english')) return 'BSED English';
  if (n.includes('history')) return 'AB History';
  if (n.includes('mathematics') || n.includes('math')) return 'BS Math';
  if (n.includes('entrepreneurship')) return 'BSEntrep';
  if (n.includes('economics')) return 'BAECO';
  if (n.includes('marketing')) return 'BAMM';
  if (n.includes('financial') || n.includes('finance')) return 'BAFM';
  if (n.includes('human resource')) return 'BAHRM';

  return name.length > 22 ? name.slice(0, 22) + '…' : name;
}

/* ─── NAVIGATION ─── */
function showPage(page) {
  currentPage = page;
  document.querySelectorAll('.page-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));

  const target = document.getElementById('page-' + page);
  if (target) target.classList.add('active');

  const navItem = document.querySelector(`.nav-item[data-page="${page}"]`);
  if (navItem) navItem.classList.add('active');

  const labels = { dashboard: 'Dashboard', applications: 'Applications', interviews: 'Interview Schedule', 'student-scheduling': 'Student Scheduling', programs: 'Programs', 'website-content': 'Website Content', reports: 'Reports', settings: 'Settings' };

  document.getElementById('breadcrumbPage').textContent = labels[page] || page;

  if (page === 'dashboard') requestAnimationFrame(() => initDashboard());
  if (page === 'applications') requestAnimationFrame(() => renderApplicationsTable());
  if (page === 'interviews') requestAnimationFrame(() => renderInterviewsTable());
  if (page === 'programs') requestAnimationFrame(() => renderProgramsTable());
  if (page === 'website-content') requestAnimationFrame(() => initWebsiteContent());
  if (page === 'reports') requestAnimationFrame(() => initReports());
  if (page === 'settings') requestAnimationFrame(() => initSettings());

  if (window.innerWidth < 768) closeSidebar();
}

function rerenderCurrentPageAfterRefresh() {
  if (currentPage === 'dashboard') {
    initDashboard();
  } else if (currentPage === 'applications') {
    applyFilters();
  } else if (currentPage === 'interviews') {
    renderInterviewsTable();
  } else if (currentPage === 'student-scheduling') {
    renderInterviewsTable();
    if (currentSchedulingCourse) {
      openStudentScheduling(currentSchedulingCourse);
    }
  } else if (currentPage === 'programs') {
    renderProgramsTable();
  } else if (currentPage === 'reports') {
    initReports();
  } else if (currentPage === 'settings') {
    initSettings();
  }
  updatePendingBadge();
}

/* ─── DASHBOARD ─── */
function initDashboard() {
  if (API_APPLICATIONS === null) {
    renderKPILoading();
    const recentListEl = document.getElementById('recentList');
    if (recentListEl) recentListEl.innerHTML = '<p class="empty-state">Loading latest applications...</p>';
  } else if (API_APPLICATIONS === 'error') {
    renderKPIError(window.LAST_API_ERROR || 'Failed to load data');
    const recentListEl = document.getElementById('recentList');
    if (recentListEl) {
      recentListEl.innerHTML = `<p class="empty-state" style="color:var(--red)">${escapeHtml(window.LAST_API_ERROR || 'Failed to load data. Please refresh the page.')}</p>`;
    }
  } else {
    renderKPIs();
    renderRecentList();
    initCharts();
  }
}

function renderKPIs() {
  const list = getApplications();
  const hasStats = DASHBOARD_STATS && Object.prototype.hasOwnProperty.call(DASHBOARD_STATS, 'total_applications');
  const total = hasStats ? Number(DASHBOARD_STATS.total_applications || 0) : list.length;
  const pending = hasStats ? Number(DASHBOARD_STATS.pending_applications || 0) : list.filter(a => a.status === 'Pending').length;
  const approved = hasStats ? Number(DASHBOARD_STATS.approved_applications || 0) : list.filter(a => a.status === 'Approved').length;
  const rejected = hasStats ? Number(DASHBOARD_STATS.rejected_applications || 0) : list.filter(a => a.status === 'Rejected').length;
  const interview = hasStats ? Number(DASHBOARD_STATS.interview_applications || 0) : list.filter(a => a.status === 'Interview Scheduled').length;

  const kpis = [
    { label: 'Total Applications', value: total, icon: 'file-text', cls: 'kpi-icon--navy', delta: `S.Y. ${new Date().getFullYear()}–${new Date().getFullYear() + 1}`, dc: '' },
    { label: 'Pending Review', value: pending, icon: 'danger', cls: 'kpi-icon--gold', delta: `${interview} in interview`, dc: 'warn' },
    { label: 'Approved', value: approved, icon: 'tick-circle', cls: 'kpi-icon--green', delta: `${Math.round(approved / total * 100 || 0)}% acceptance rate`, dc: 'up' },
    { label: 'Interview Scheduled', value: interview, icon: 'calendar', cls: 'kpi-icon--blue', delta: 'Awaiting results', dc: '' },
    { label: 'Rejected', value: rejected, icon: 'close-circle', cls: 'kpi-icon--red', delta: `${Math.round(rejected / total * 100 || 0)}% rejection rate`, dc: '' },
  ];

  document.getElementById('kpiGrid').innerHTML = kpis.map(k => `
    <div class="kpi-card" data-kpi-target="${k.value}">
      <div class="kpi-icon ${k.cls}">
        <i data-iconsax="${k.icon}"></i>
      </div>
      <div class="kpi-body">
        <div class="kpi-value">0</div>
        <div class="kpi-label">${k.label}</div>
        <div class="kpi-delta ${k.dc}">${k.delta}</div>
      </div>
    </div>
  `).join('');

  // Trigger counter animation
  document.querySelectorAll('.kpi-card').forEach(card => {
    const target = parseInt(card.dataset.kpiTarget, 10);
    const valueEl = card.querySelector('.kpi-value');
    if (isNaN(target) || !valueEl) return;
    
    let current = 0;
    const duration = 1500;
    const step = (timestamp) => {
      if (!card.startTime) card.startTime = timestamp;
      const progress = Math.min((timestamp - card.startTime) / duration, 1);
      valueEl.textContent = Math.floor(progress * target);
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  });

  if (typeof iconsax !== 'undefined') iconsax.createIcons();
  updatePendingBadge();
}

/** Initial empty state for Scheduling Table */
function renderSchedulingSkeleton() {
  const tbody = document.getElementById('studentSchedulingTableBody');
  if (!tbody) return;
  tbody.innerHTML = Array(5).fill(0).map(() => `
    <tr class="skeleton-row">
      <td colspan="6"><div class="skeleton-line" style="height:20px;margin:10px 0;"></div></td>
    </tr>
  `).join('');
}

/** Initial empty state for KPIs */
function renderKPILoading() {
  const grid = document.getElementById('kpiGrid');
  if (!grid) return;
  grid.innerHTML = Array(5).fill(0).map(() => `
    <div class="kpi-card loading" style="opacity:0.6; pointer-events:none;">
      <div class="kpi-icon kpi-icon--navy" style="background:#f1f5f9"></div>
      <div class="kpi-body">
        <div class="kpi-value" style="width:40px;height:24px;background:#f1f5f9;border-radius:4px;margin-bottom:8px"></div>
        <div class="kpi-label" style="width:80px;height:12px;background:#f1f5f9;border-radius:4px"></div>
      </div>
    </div>
  `).join('');
}

/** Error state for KPIs */
function renderKPIError(errorMessage = 'Failed to load') {
  const grid = document.getElementById('kpiGrid');
  if (!grid) return;
  grid.innerHTML = Array(5).fill(0).map(() => `
    <div class="kpi-card">
      <div class="kpi-icon kpi-icon--red"><i data-iconsax="alert-triangle"></i></div>
      <div class="kpi-body">
        <div class="kpi-value" style="font-size:16px;color:var(--red)">Error</div>
        <div class="kpi-label">${escapeHtml(errorMessage)}</div>
      </div>
    </div>
  `).join('');
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
}

function renderRecentList() {
  const recent = [...getApplications()].sort((a, b) => new Date(b.filed || 0) - new Date(a.filed || 0)).slice(0, 5);
  const recentListEl = document.getElementById('recentList');
  recentListEl.innerHTML = recent.map(app => `
    <div class="recent-item" data-app-id="${app.id}" role="button" tabindex="0">
      <div class="recent-avatar">${initials(app)}</div>
      <div class="recent-info">
        <div class="recent-name">${escapeHtml(fullNameDisplay(app))}</div>
        <div class="recent-program">${shortProg(app.firstChoice)}</div>
      </div>
      <span class="badge ${statusClass(app.status)}">${app.status}</span>
    </div>
  `).join('');
  recentListEl.querySelectorAll('.recent-item').forEach(el => {
    el.addEventListener('click', function () { openSlideoverById(Number(this.getAttribute('data-app-id'))); });
    el.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openSlideoverById(Number(this.getAttribute('data-app-id'))); } });
  });
}



function initCharts() {
  if (trendChart && typeChart && programChart) return;

  Chart.defaults.font.family = "'DM Sans', sans-serif";
  Chart.defaults.color = '#64748b';
  const NAVY2 = '#254d82', GOLD = '#c9933a', GREEN = '#16a34a', BLUE = '#2563eb', NAVY = '#1b3557';

  // Trend (from server-side stats)
  let trendLabels = [], trendData = [];
  if (DASHBOARD_STATS && Array.isArray(DASHBOARD_STATS.monthly_trend) && DASHBOARD_STATS.monthly_trend.length > 0) {
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    trendLabels = DASHBOARD_STATS.monthly_trend.map(t => {
      const [y, m] = t.month.split('-');
      return monthLabels[parseInt(m, 10) - 1] + ' ' + y;
    });
    trendData = DASHBOARD_STATS.monthly_trend.map(t => t.count);
  } else {
    // Fallback: client-side
    const monthCounts = {};
    getApplications().forEach(a => {
      if (a.filed) {
        const d = new Date(a.filed);
        const key = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
        monthCounts[key] = (monthCounts[key] || 0) + 1;
      }
    });
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const sortedMonths = Object.keys(monthCounts).sort();
    trendLabels = sortedMonths.map(ym => {
      const [y, m] = ym.split('-');
      return monthLabels[parseInt(m, 10) - 1] + ' ' + y;
    });
    trendData = sortedMonths.map(ym => monthCounts[ym]);
  }
  if (trendChart) trendChart.destroy();
  trendChart = new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
      labels: trendLabels.length ? trendLabels : ['No data'],
      datasets: [{ label: 'Applications', data: trendData.length ? trendData : [0], borderColor: NAVY2, backgroundColor: 'rgba(37,77,130,.08)', fill: true, tension: .42, pointBackgroundColor: NAVY2, pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.05)' } }, x: { grid: { display: false } } } }
  });

  // Donut
  const typeCounts = {};
  getApplications().forEach(a => { typeCounts[a.type] = (typeCounts[a.type] || 0) + 1; });
  const typeColors = [NAVY, GOLD, GREEN, BLUE];
  if (typeChart) typeChart.destroy();
  typeChart = new Chart(document.getElementById('typeChart'), {
    type: 'doughnut',
    data: { labels: Object.keys(typeCounts), datasets: [{ data: Object.values(typeCounts), backgroundColor: typeColors, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '68%' }
  });
  document.getElementById('donutLegend').innerHTML = Object.keys(typeCounts).map((k, i) => `
    <div class="legend-item"><div class="legend-dot" style="background:${typeColors[i]}"></div><span>${k} (${typeCounts[k]})</span></div>
  `).join('');

  // Program bar
  const progCounts = {};
  getApplications().forEach(a => { progCounts[a.firstChoice] = (progCounts[a.firstChoice] || 0) + 1; });
  const sorted = Object.entries(progCounts).sort((a, b) => b[1] - a[1]).slice(0, 6);
  if (programChart) programChart.destroy();
  programChart = new Chart(document.getElementById('programChart'), {
    type: 'bar',
    data: { labels: sorted.map(([k]) => shortProg(k)), datasets: [{ label: 'Applicants', data: sorted.map(([, v]) => v), backgroundColor: NAVY2, borderRadius: 6, borderSkipped: false }] },
    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.05)' } }, y: { grid: { display: false } } } }
  });
}

/* ─── APPLICATIONS TABLE ─── */
function renderApplicationsTable() {
  const sel = document.getElementById('filterProgram');
  if (sel && sel.options.length <= 1) {
    const programs = getPrograms();
    const names = new Set(programs.map(p => p.name));
    getApplications().forEach(a => { if (a.firstChoice) names.add(a.firstChoice); });
    names.forEach(name => {
      const opt = document.createElement('option');
      opt.value = name;
      opt.textContent = shortProg(name);
      sel.appendChild(opt);
    });
  }
  applyFilters();
}

function applyFilters() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const type = document.getElementById('filterType').value;
  const status = document.getElementById('filterStatus').value;
  const program = document.getElementById('filterProgram').value;

  filteredApps = getApplications().filter(a => {
    const fullName = `${a.surname} ${a.firstName} ${a.middleName || ''}`.trim().toLowerCase();
    return (!search || fullName.includes(search) || (a.ref || '').toLowerCase().includes(search))
      && (!type || a.type === type)
      && (!status || a.status === status)
      && (!program || a.firstChoice === program);
  });
  appPage = 1;
  renderTable();
  renderPagination();
}

function renderTable() {
  const totalPages = Math.max(1, Math.ceil(filteredApps.length / APP_PER_PAGE));
  appPage = Math.min(Math.max(appPage, 1), totalPages);
  const start = (appPage - 1) * APP_PER_PAGE;
  const slice = filteredApps.slice(start, start + APP_PER_PAGE);
  const tbody = document.getElementById('appTableBody');

  if (!slice.length) {
    const appLoadFailed = API_APPLICATIONS === 'error';
    tbody.innerHTML = `<tr><td colspan="8"><div class="empty-state">
      <i data-iconsax="${appLoadFailed ? 'warning-2' : 'zoom-in'}" style="width:20px;height:20px"></i>
      <p>${appLoadFailed ? 'Applications could not be loaded. Please refresh or sign in again.' : 'No applications match your filters.'}</p>
    </div></td></tr>`;
    document.getElementById('tableInfo').textContent = appLoadFailed ? (window.LAST_API_ERROR || 'Failed to load applications') : 'No results found';
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
    return;
  }

  tbody.innerHTML = slice.map(app => `
    <tr data-app-id="${app.id}">
      <td class="ref-col">${escapeHtml(app.ref)}</td>
      <td class="name-col">${escapeHtml(fullNameDisplay(app))}</td>
      <td style="text-align: center">${escapeHtml(app.type)}</td>
      <td style="text-align: center">${escapeHtml(shortProg(app.firstChoice))}</td>
      <td class="gwa-col" style="text-align: center">${avgGWA(app)}</td>
      <td>${formatDate(app.filed)}</td>
      <td style="text-align: center"><span class="badge ${statusClass(app.status)}">${escapeHtml(app.status)}</span></td>
      <td style="text-align: center">
        <div class="action-btns-cell">
          <button type="button" class="btn-view-label btn-view-app" title="View Details" data-app-id="${app.id != null ? app.id : ''}" data-app-ref="${escapeHtml(app.ref || '')}">View</button>
          <button type="button" class="btn-view-label btn-delete-app" style="background:#fee2e2; color:#b91c1c;" title="Delete Application" data-app-id="${app.id != null ? app.id : ''}" data-app-ref="${escapeHtml(app.ref || '')}">Delete</button>
        </div>
      </td>
    </tr>
  `).join('');

  if (typeof iconsax !== 'undefined') iconsax.createIcons();

  document.getElementById('tableInfo').textContent =
    `Showing ${start + 1}–${Math.min(start + APP_PER_PAGE, filteredApps.length)} of ${filteredApps.length} applications`;
}

function renderPagination() {
  const total = Math.ceil(filteredApps.length / APP_PER_PAGE);
  const pg = document.getElementById('pagination');
  if (total <= 1) { pg.innerHTML = ''; return; }
  let html = `<button class="page-btn" onclick="goToPage(${appPage - 1})" ${appPage === 1 ? 'disabled' : ''}>Previous</button>`;
  for (let i = 1; i <= total; i++) {
    html += `<button class="page-btn ${i === appPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
  }
  html += `<button class="page-btn" onclick="goToPage(${appPage + 1})" ${appPage === total ? 'disabled' : ''}>Next</button>`;
  pg.innerHTML = html;
}

function goToPage(n) {
  const total = Math.max(1, Math.ceil(filteredApps.length / APP_PER_PAGE));
  appPage = Math.min(Math.max(n, 1), total);
  renderTable();
  renderPagination();
}

function changeStatus(ref, newStatus) {
  const app = getApplications().find(a => a.ref === ref);
  if (app) changeStatusById(app.id, newStatus);
}

function changeStatusById(appId, newStatus) {
  const app = getApplications().find(a => a.id === appId || a.id === Number(appId));
  if (!app) {
    showToast('Application not found. Try refreshing the page.');
    return;
  }
  const id = app.id != null ? Number(app.id) : NaN;
  if (!id || isNaN(id) || id < 1) {
    showToast('Cannot update: invalid application ID.');
    return;
  }
  const apiStatus = mapStatusToApi(newStatus);
  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
    showToast('Please log in to update status.');
    return;
  }
  AdmissionAPI.updateApplicationStatus(id, apiStatus, '').then(() => {
    app.status = newStatus;
    showToast('Status updated to "' + newStatus + '" for ' + (app.surname || '') + ', ' + (app.firstName || ''));
    renderKPIs();
    renderRecentList();
    applyFilters();
    updatePendingBadge();
    var badge = document.getElementById('slideoverBadge');
    var panel = document.getElementById('slideover');
    if (badge && panel && panel.classList.contains('open')) {
      badge.className = 'badge ' + statusClass(newStatus);
      badge.textContent = newStatus;
    }
    refreshDataSoon();
  }).catch(function (e) {
    var msg = e.message || 'Update failed';
    if (e.data) {
      if (e.data.message) msg = e.data.message;
      if (e.data.errors && typeof e.data.errors === 'object') {
        var first = Object.values(e.data.errors)[0];
        if (Array.isArray(first) && first[0]) msg = first[0];
        else if (typeof first === 'string') msg = first;
      }
    }
    showToast(msg);
  });
}

function deleteApplicationById(appId, ref) {
  showConfirmModal({
    title: "Delete Application?",
    message: `Are you sure you want to delete application <strong>${ref}</strong>? This will permanently remove the record and restore the program slot.`,
    confirmText: "Delete",
    onConfirm: () => performDelete(appId, ref)
  });
}

function performDelete(appId, ref) {
  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
    showToast('Please log in to delete applications.');
    return;
  }

  AdmissionAPI.deleteApplication(appId).then(() => {
    showToast(`Application ${ref} deleted successfully.`);
    // Update local data
    if (Array.isArray(API_APPLICATIONS)) {
      API_APPLICATIONS = API_APPLICATIONS.filter(a => Number(a.id) !== Number(appId));
    }
    filteredApps = filteredApps.filter(a => Number(a.id) !== Number(appId));
    
    renderKPIs();
    renderRecentList();
    applyFilters();
    updatePendingBadge();
    refreshDataSoon();
  }).catch(e => {
    showToast('Deletion failed: ' + (e.message || 'Unknown error'));
    console.error('Delete error:', e);
  });
}

const APPLICATION_CSV_COLUMNS = [
  ['Timestamp', a => formatCsvDateTime(csvField(a, ['submitted_at', 'created_at'], a.filed))],
  ['Reference No.', a => csvField(a, ['reference_number', 'application_no'], a.ref)],
  ['Email Address', a => csvField(a, ['email'])],
  ['I am a/an', a => csvField(a, ['applicant_type', 'application_type'], a.type)],
  ['Surname', a => csvField(a, ['last_name'], a.surname)],
  ['Name', a => csvField(a, ['first_name'], a.firstName)],
  ['Middle Name', a => csvField(a, ['middle_name'], a.middleName)],
  ['Suffix', a => csvField(a, ['suffix'])],
  ['Sex', a => csvField(a, ['sex', 'gender'], a.sex)],
  ['Date of Birth', a => formatCsvDate(csvField(a, ['date_of_birth']))],
  ['Place of Birth', a => csvField(a, ['place_of_birth'])],
  ['Civil Status', a => csvField(a, ['civil_status'])],
  ['Contact Number', a => csvField(a, ['contact_number', 'phone', 'mobile_number'])],
  ['Permanent Address', a => csvField(a, ['permanent_address'])],
  ['Present Address', a => csvField(a, ['present_address'])],
  ['Father Name', a => csvField(a, ['father_name'])],
  ['Father Contact', a => csvField(a, ['father_contact'])],
  ['Mother Name', a => csvField(a, ['mother_name'])],
  ['Mother Contact', a => csvField(a, ['mother_contact'])],
  ['Elementary School', a => csvField(a, ['elementary_school'])],
  ['Elementary Year Graduated', a => csvField(a, ['elementary_year_graduated'])],
  ['Junior High School', a => csvField(a, ['junior_high_school'])],
  ['Junior High Year Graduated', a => csvField(a, ['junior_high_year_graduated'])],
  ['Senior High School', a => csvField(a, ['senior_high_school'])],
  ['Senior High Year Graduated', a => csvField(a, ['senior_high_year_graduated'])],
  ['Previous College', a => csvField(a, ['previous_college'])],
  ['Previous College Year Last Attended', a => csvField(a, ['previous_college_year_last_attended'])],
  ['First Choice Program', a => csvField(a, ['first_choice', 'program_name'], a.firstChoice)],
  ['Second Choice Program', a => csvField(a, ['second_choice'])],
  ['GWA Grade 11', a => csvField(a, ['gwa_grade_11'], a.g11)],
  ['GWA Grade 12', a => csvField(a, ['gwa_grade_12'], a.g12)],
  ['PWD', a => csvField(a, ['pwd', 'differently_abled'], a.pwd)],
  ['Solo Parent', a => csvField(a, ['solo_parent', 'solo'], a.solo)],
  ['Indigenous', a => csvField(a, ['indigenous', 'indigenous_person'], a.indigenous)],
  ['4Ps', a => csvField(a, ['four_ps', 'fourPs', 'fours'], a.fours)],

];

function csvField(app, keys, fallback = '') {
  const raw = app && app.raw ? app.raw : app || {};
  for (const key of keys) {
    if (raw[key] !== undefined && raw[key] !== null && raw[key] !== '') return raw[key];
  }
  return fallback == null ? '' : fallback;
}

function csvCell(value) {
  if (value === null || value === undefined) return '';
  const text = Array.isArray(value) || typeof value === 'object' ? JSON.stringify(value) : String(value);
  return `"${text.replace(/"/g, '""').replace(/\r?\n/g, ' ')}"`;
}

function formatCsvDate(value) {
  if (!value) return '';
  const text = String(value);
  const match = text.match(/^(\d{4})-(\d{2})-(\d{2})/);
  if (match) return `${match[2]}/${match[3]}/${match[1]}`;
  const parsed = new Date(text);
  if (Number.isNaN(parsed.getTime())) return text;
  return parsed.toLocaleDateString('en-US');
}

function formatCsvDateTime(value) {
  if (!value) return '';
  const text = String(value);
  const match = text.match(/^(\d{4})-(\d{2})-(\d{2})[T\s](\d{2}):(\d{2})(?::(\d{2}))?/);
  if (match) {
    const hour24 = Number(match[4]);
    const suffix = hour24 >= 12 ? 'PM' : 'AM';
    const hour12 = hour24 % 12 || 12;
    return `${match[2]}/${match[3]}/${match[1]} ${hour12}:${match[5]} ${suffix}`;
  }
  const parsed = new Date(text);
  if (Number.isNaN(parsed.getTime())) return text;
  return parsed.toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: 'numeric',
    minute: '2-digit',
  });
}

function excelCell(value) {
  if (value === null || value === undefined) return '';
  const text = Array.isArray(value) || typeof value === 'object' ? JSON.stringify(value) : String(value);
  return escapeHtml(text).replace(/\r?\n/g, '<br>');
}

function exportCSV(data, filename) {
  const headers = APPLICATION_CSV_COLUMNS.map(([label]) => label);
  const rows = data.map(app => APPLICATION_CSV_COLUMNS.map(([, getValue]) => getValue(app)));
  const html = `
    <html>
      <head>
        <meta charset="UTF-8">
        <style>
          body {
            margin: 0;
            background: #ffffff;
          }
          table {
            border-collapse: collapse;
            font-family: Calibri, Arial, sans-serif;
            font-size: 10.5pt;
            color: #172033;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
          }
          th {
            background: #17365d;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #102640;
            padding: 10px 12px;
            white-space: nowrap;
            height: 28px;
          }
          td {
            border: 1px solid #b8c4d4;
            padding: 8px 12px;
            vertical-align: middle;
            text-align: center;
            line-height: 1.35;
            height: 24px;
            mso-number-format: "\\@";
          }
          tr:nth-child(even) td {
            background: #f7f9fc;
          }
          .title {
            background: #0f1e3d;
            color: #ffffff;
            font-size: 18pt;
            font-weight: 700;
            text-align: left;
            padding: 16px 14px;
            border: 1px solid #0f1e3d;
            height: 34px;
          }
          .subtitle {
            background: #eef3f9;
            color: #24364f;
            font-weight: 600;
            text-align: left;
            padding: 10px 14px;
            border: 1px solid #b8c4d4;
            height: 26px;
          }
          .spacer td {
            background: #ffffff;
            border: none;
            height: 10px;
            padding: 0;
          }
        </style>
      </head>
      <body>
        <table>
          <tr><td class="title" colspan="${headers.length}">BTECH Admission Applications Export</td></tr>
          <tr><td class="subtitle" colspan="${headers.length}">Generated ${new Date().toLocaleString()} | ${data.length} record(s)</td></tr>
          <tr class="spacer"><td colspan="${headers.length}"></td></tr>
          <tr>${headers.map(label => `<th>${excelCell(label)}</th>`).join('')}</tr>
          ${rows.map(row => `<tr>${row.map(value => `<td>${excelCell(value)}</td>`).join('')}</tr>`).join('')}
        </table>
      </body>
    </html>
  `;
  const blob = new Blob(['\uFEFF' + html], { type: 'application/vnd.ms-excel;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  const exportName = filename ? filename.replace(/\.csv$/i, '.xls') : 'applications.xls';
  a.href = url; a.download = exportName; a.click();
  URL.revokeObjectURL(url);
  showToast('Styled Excel file exported successfully');
}

function exportReportsCSV() {
  const headers = ['Program', 'Department', 'Total Applications', 'Approved', 'Pending', 'Rejected', 'Avg GWA'];
  let rows = [];

  if (DASHBOARD_STATS && Array.isArray(DASHBOARD_STATS.by_program) && DASHBOARD_STATS.by_program.length > 0) {
    const programs = getPrograms();
    rows = DASHBOARD_STATS.by_program.map(row => {
      const prog = programs.find(p => p.name === row.first_choice);
      const dept = prog ? (prog.department || '—') : '—';
      const gwa = row.avg_gwa != null ? parseFloat(row.avg_gwa).toFixed(1) : '—';
      return [row.first_choice || '—', dept, row.total || 0, row.approved || 0, row.pending || 0, row.rejected || 0, gwa];
    });
  } else {
    const progCounts = {}, progStatuses = {}, progGWAs = {};
    getApplications().forEach(a => {
      const p = a.firstChoice;
      if (!p) return;
      progCounts[p] = (progCounts[p] || 0) + 1;
      progStatuses[p] = progStatuses[p] || { Approved: 0, Pending: 0, Rejected: 0, 'Interview Scheduled': 0 };
      progStatuses[p][a.status] = (progStatuses[p][a.status] || 0) + 1;
      progGWAs[p] = progGWAs[p] || [];
      const g11 = parseFloat(a.g11); const g12 = parseFloat(a.g12);
      if (!isNaN(g11) && !isNaN(g12)) progGWAs[p].push((g11 + g12) / 2);
      else if (!isNaN(g11)) progGWAs[p].push(g11);
      else if (!isNaN(g12)) progGWAs[p].push(g12);
    });
    rows = Object.entries(progCounts).sort((a, b) => b[1] - a[1]).map(([name, count]) => {
      const st = progStatuses[name] || {};
      const gwaArr = progGWAs[name] || [];
      const gwa = gwaArr.length ? (gwaArr.reduce((s, x) => s + x, 0) / gwaArr.length).toFixed(1) : '—';
      const prog = getPrograms().find(p => p.name === name);
      return [name, prog ? (prog.department || '—') : '—', count, st.Approved || 0, (st.Pending || 0) + (st['Interview Scheduled'] || 0), st.Rejected || 0, gwa];
    });
  }

  const csvRows = rows.map(r => r.map(v => `"${String(v || '').replace(/"/g, '""')}"`).join(','));
  const csv = [headers.join(','), ...csvRows].join('\n');
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = 'btech_report.csv'; a.click();
  URL.revokeObjectURL(url);
  showToast('Report CSV exported successfully');
}

/* ─── SLIDE-OVER ─── */
function openSlideover(ref) {
  const app = getApplications().find(a => (a.ref || '') === ref);
  if (app) {
    openSlideoverByApp(app);
  } else {
    openSlideoverEmpty('Application not found. Try refreshing the page.');
  }
}

function openSlideoverById(appId) {
  const id = appId != null ? Number(appId) : NaN;
  if (id === undefined || id === null || isNaN(id) || id < 1) {
    openSlideoverEmpty('Invalid application.');
    return;
  }
  const app = getApplications().find(a => a.id === id || a.id === appId);
  if (app) {
    openSlideoverByApp(app);
  } else {
    openSlideoverEmpty('Application not found. Try refreshing the page.');
  }
}

function openSlideoverEmpty(message) {
  var overlay = document.getElementById('slideoverOverlay');
  var panel = document.getElementById('slideover');
  var titleEl = document.getElementById('slideoverTitle');
  var refEl = document.getElementById('slideoverRef');
  var bodyEl = document.getElementById('slideoverBody');
  if (!overlay || !panel) return;
  overlay.classList.add('open');
  panel.classList.add('open');
  if (titleEl) titleEl.textContent = 'Details';
  if (refEl) refEl.textContent = '—';
  if (bodyEl) bodyEl.innerHTML = '<p class="empty-state">' + escapeHtml(message || 'No details available.') + '</p>';
}

/** Normalize API detail or list app into one object for slide-over display (matches form in index.html) */
function normalizeDetailForSlideover(d) {
  if (!d) return {};
  // Support nested API shape: { application: {}, applicant: {} } — flatten for lookups
  var flat = d.applicant && typeof d.applicant === 'object'
    ? Object.assign({}, d, d.applicant)
    : d;
  var parts = function (a, b, c, d2, e) {
    return [a, b, c, d2, e].filter(Boolean).join(', ');
  };
  var perm = flat.address_line1 || flat.permCity || flat.city || flat.province;
  var permAddr = perm ? parts(flat.address_line1, flat.address_line2, flat.city, flat.province, flat.postal_code) : (flat.permCity ? flat.permCity + (flat.province ? ', ' + flat.province : '') : null) || (flat.city || flat.province ? parts(flat.address_line1, flat.address_line2, flat.city, flat.province, flat.postal_code) : null);
  var presAddr = flat.present_address || flat.presentAddress || (flat.presentCity || flat.presCity ? parts(flat.presentCity, flat.presProvince) : null) || (flat.presCity ? flat.presCity + ', ' + (flat.province || 'Bulacan') : null);
  var sex = (flat.gender || flat.sex || '').toString().toLowerCase();
  if (sex === 'male') sex = 'Male'; else if (sex === 'female') sex = 'Female';
  var applicantTypeRaw = (
    flat.applicant_type != null ? flat.applicant_type :
      (flat.application_type != null ? flat.application_type :
        (flat.type != null ? flat.type : (flat.respondentType || '')))
  );
  var applicantTypeNorm = String(applicantTypeRaw || '').trim().toLowerCase();
  var applicantTypeLabel = applicantTypeNorm === 'transferee'
    ? 'Transferee'
    : applicantTypeNorm === 'returnee'
      ? 'Returnee'
      : applicantTypeNorm === 'als graduate' || applicantTypeNorm === 'als_graduate'
        ? 'ALS Graduate'
        : applicantTypeNorm === 'new' || applicantTypeNorm === 'freshmen' || applicantTypeNorm === 'freshman'
          ? 'Freshmen'
          : (applicantTypeRaw || flat.type || flat.respondentType || '');

  return {
    surname: flat.last_name != null ? flat.last_name : flat.surname,
    firstName: flat.first_name != null ? flat.first_name : flat.firstName,
    middleName: flat.middle_name != null ? flat.middle_name : flat.middleName,
    suffix: flat.suffix,
    birthdate: flat.date_of_birth || flat.birthdate || flat.dateOfBirth,
    placeOfBirth: flat.place_of_birth || flat.birthplace || flat.placeOfBirth || flat.permCity,
    sex: sex,
    civilStatus: flat.civil_status || flat.civilStatus,
    phone: flat.contact_number || flat.phone || flat.studentContactNumber,
    email: flat.applicant_email != null ? flat.applicant_email : (flat.email || flat.studentEmail),
    permAddr: flat.permanent_address || permAddr || (flat.permCity ? flat.permCity + ', Bulacan' : null) || (flat.permanentBarangay ? [flat.permanentBarangay, flat.permanentCity, flat.permanentProvince, flat.permanentZipCode].filter(Boolean).join(', ') : null) || (flat.city && flat.province ? flat.city + ', ' + flat.province : null),
    presAddr: flat.present_address || presAddr || (flat.presCity ? flat.presCity + ', Bulacan' : null) || (flat.presentBarangay ? [flat.presentBarangay, flat.presentCity, flat.presentProvince, flat.presentZipCode].filter(Boolean).join(', ') : null),
    type: applicantTypeLabel,
    firstChoice: flat.first_choice || flat.program_name || flat.firstChoice,
    secondChoice: flat.second_choice != null ? flat.second_choice : flat.secondChoice,
    g11: flat.gwa_grade_11 != null ? flat.gwa_grade_11 : (flat.g11 || flat.grade11GWA),
    g12: flat.gwa_grade_12 != null ? flat.gwa_grade_12 : (flat.g12 || flat.grade12GWA),
    lastSchool: flat.last_school || flat.lastSchool,
    schoolAddress: flat.school_address || flat.schoolAddress,
    yearGraduated: flat.year_graduated != null ? flat.year_graduated : flat.yearGraduated,
    elementarySchool: flat.elementary_school || flat.elementarySchool || flat.elemSchool,
    elementaryYear: flat.elementary_year_graduated != null ? flat.elementary_year_graduated : (flat.elementary_year != null ? flat.elementary_year : (flat.elementaryYear || flat.elemYear)),
    juniorHighSchool: flat.junior_high_school || flat.juniorHighSchool || flat.hsSchool,
    juniorHighYear: flat.junior_high_year_graduated != null ? flat.junior_high_year_graduated : (flat.junior_high_year != null ? flat.junior_high_year : (flat.juniorHighYear || flat.hsYear)),
    seniorHighSchool: flat.senior_high_school || flat.seniorHighSchool || flat.shsSchool || flat.last_school || flat.lastSchool,
    seniorHighYear: flat.senior_high_year_graduated != null ? flat.senior_high_year_graduated : (flat.senior_high_year != null ? flat.senior_high_year : (flat.seniorHighYear || flat.shsYear || flat.year_graduated || flat.yearGraduated)),
    collegeSchool: flat.previous_college || flat.college_school || flat.collegeSchool || flat.tertiarySchool,
    collegeYear: flat.previous_college_year_last_attended != null ? flat.previous_college_year_last_attended : (flat.college_year != null ? flat.college_year : (flat.collegeYear || flat.tertiaryYear)),
    fatherName: flat.father_name || flat.fatherName || (flat.fatherFirstName ? [flat.fatherFirstName, flat.fatherMiddleName, flat.fatherSurname].filter(Boolean).join(' ') : null),
    fatherContact: flat.father_contact != null ? flat.father_contact : (flat.fatherContact || flat.contactNumber),
    motherName: flat.mother_name || flat.motherName || (flat.motherFirstName ? [flat.motherFirstName, flat.motherMiddleName, flat.motherMaidenName].filter(Boolean).join(' ') : null),
    motherContact: flat.mother_contact != null ? flat.mother_contact : (flat.motherContact || flat.alternateContactNumber),
    ref: flat.application_no || flat.reference_number || flat.ref,
    filed: flat.submitted_at || flat.filed,
    status: mapApiStatus(flat.status || flat.current_status),
    pwd: flat.pwd || flat.differentlyAbled,
    solo: flat.solo_parent || flat.solo || flat.soloParent,
    indigenous: flat.indigenous || flat.indigenous_person || flat.indigenousPerson,
    fours: flat.four_ps || flat.fours || flat.fourPs,
    photoPath: flat.photo_path || flat.photoPath || null
  };
}

function openSlideoverByApp(app) {
  if (!app) return;

  var overlay = document.getElementById('slideoverOverlay');
  var panel = document.getElementById('slideover');
  if (!overlay || !panel) return;

  overlay.classList.add('open');
  panel.classList.add('open');

  var titleEl = document.getElementById('slideoverTitle');
  var refEl = document.getElementById('slideoverRef');
  var bodyEl = document.getElementById('slideoverBody');
  if (!bodyEl) return;
  if (titleEl) titleEl.textContent = fullNameDisplay(app);
  if (refEl) refEl.textContent = app.ref || '—';

  bodyEl.innerHTML = '<p class="empty-state">Loading details…</p>';

  function renderWith(data) {
    var o = normalizeDetailForSlideover(data);
    var safe = function (v) { return v != null && v !== '' ? String(v) : '—'; };
    var safeDate = function (d) { try { return d != null ? formatDate(d) : '—'; } catch (e) { return '—'; } };
    var formatPhone = function (p) {
      if (p == null || p === '') return '—';
      var s = String(p).replace(/\D/g, '');
      if (s.length >= 10) return '+63 ' + s.replace(/^0?/, '').replace(/(\d{3})(\d{3})(\d{4})$/, '$1-$2-$3');
      return safe(p);
    };
    var dispRef = o.ref != null ? o.ref : app.ref;
    var dispFiled = o.filed != null ? o.filed : app.filed;
    var dispStatus = o.status != null ? o.status : app.status;
    var dispType = o.type != null ? o.type : app.type;
    var dispFirst = o.firstChoice != null ? o.firstChoice : app.firstChoice;
    var dispSecond = o.secondChoice != null ? o.secondChoice : app.secondChoice;
    var dispG11 = o.g11 != null && o.g11 !== '' ? o.g11 : (app.g11 != null && app.g11 !== '' ? app.g11 : '—');
    var dispG12 = o.g12 != null && o.g12 !== '' ? o.g12 : (app.g12 != null && app.g12 !== '' ? app.g12 : '—');
    var isYes = function (v) { return String(v || '').trim().toLowerCase() === 'yes'; };
    var dispPwd = isYes(o.pwd || app.pwd) ? 'Yes' : 'No';
    var dispSolo = isYes(o.solo || app.solo) ? 'Yes' : 'No';
    var dispIndigenous = isYes(o.indigenous || app.indigenous) ? 'Yes' : 'No';
    var dispFours = isYes(o.fours || app.fours) ? 'Yes' : 'No';
    var appForGwa = { g11: o.g11 != null ? o.g11 : app.g11, g12: o.g12 != null ? o.g12 : app.g12 };
    var isTransferee = String(dispType || '').trim().toLowerCase() === 'transferee';
    var hasCollegeData = !!(o.collegeSchool || o.collegeYear);

    try {
      bodyEl.innerHTML = `
    <div class="app-form">
      <div class="form-header-with-photo">
        <div>
          <div class="form-college">BALIWAG POLYTECHNIC COLLEGE</div>
          <div class="form-subtitle">College Admission Application</div>
        </div>
        <div class="form-photo-box" style="overflow:hidden;padding:0;">
          ${o.photoPath
          ? `<img src="${storageUrl(o.photoPath)}" alt="Applicant Photo" style="width:100%;height:100%;object-fit:cover;border-radius:6px;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" /><div style="display:none;flex-direction:column;align-items:center;justify-content:center;width:100%;height:100%;"><i data-iconsax="user" style="width:28px;height:28px;color:#94a3b8"></i><div class='form-photo-label'>2x2 Photo</div></div>`
          : `<i data-iconsax="user" style="width:28px;height:28px;color:#94a3b8"></i><div class="form-photo-label">2x2 Photo</div>`
        }
        </div>
      </div>

      <div class="form-row-header">
        <div class="form-ref-box">
          <div class="form-label">REFERENCE NO.</div>
          <div class="form-value">${safe(dispRef)}</div>
        </div>
        <div class="form-date-box">
          <div class="form-label">DATE FILED</div>
          <div class="form-value">${safeDate(dispFiled)}</div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION I — APPLICANT'S PERSONAL INFORMATION</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Surname</label>
            <div class="form-field-value">${safe(o.surname)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">First Name</label>
            <div class="form-field-value">${safe(o.firstName)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Middle Name</label>
            <div class="form-field-value">${escapeHtml(safe(o.middleName))}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Suffix</label>
            <div class="form-field-value">${safe(o.suffix)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Date of Birth</label>
            <div class="form-field-value">${safeDate(o.birthdate)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Place of Birth</label>
            <div class="form-field-value">${safe(o.placeOfBirth)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Sex</label>
            <div class="form-field-value" style="font-size:13px">
              <span class="form-checkbox">${(o.sex || '').toString() === 'Male' ? '☑️' : '☐'} Male</span>
              <span class="form-checkbox">${(o.sex || '').toString() === 'Female' ? '☑️' : '☐'} Female</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Civil Status</label>
            <div class="form-field-value">${safe(o.civilStatus)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Student Contact Number</label>
            <div class="form-field-value">${o.phone ? '(+63) ' + String(o.phone).replace(/^(\d{3})(\d{3})(\d{4})$/, '$1-$2-$3') : '—'}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Email Address</label>
            <div class="form-field-value no-uppercase">${safe(o.email)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Application Type</label>
            <div class="form-field-value">${safe(dispType)}</div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION II — ADDRESS INFORMATION</div>
        <div class="form-grid-1">
          <div class="form-field">
            <label class="form-field-label">Permanent Address</label>
            <div class="form-field-value">${safe(o.permAddr)}</div>
          </div>
        </div>
        <div class="form-grid-1">
          <div class="form-field">
            <label class="form-field-label">Present Address</label>
            <div class="form-field-value">${(o.presAddr != null && o.presAddr !== '') ? safe(o.presAddr) : (o.permAddr != null && o.permAddr !== '' ? 'Same as permanent address' : '—')}</div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION III — FAMILY & CONTACT INFORMATION</div>
        <p style="font-size:12px;color:var(--text-3);margin-bottom:8px">Parent/guardian details may be collected in the application form; display here when available.</p>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Father's Full Name</label>
            <div class="form-field-value">${safe(o.fatherName)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Father's Contact</label>
            <div class="form-field-value">${formatPhone(o.fatherContact)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Mother's Full Name</label>
            <div class="form-field-value">${safe(o.motherName)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Mother's Contact</label>
            <div class="form-field-value">${formatPhone(o.motherContact)}</div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION IV — ACADEMIC BACKGROUND</div>
        <p class="form-subsection-label">Elementary School</p>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">School Name</label>
            <div class="form-field-value">${safe(o.elementarySchool)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Year Graduated</label>
            <div class="form-field-value">${safe(o.elementaryYear)}</div>
          </div>
        </div>
        <p class="form-subsection-label">Junior High School</p>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">School Name</label>
            <div class="form-field-value">${safe(o.juniorHighSchool)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Year Graduated</label>
            <div class="form-field-value">${safe(o.juniorHighYear)}</div>
          </div>
        </div>
        <p class="form-subsection-label">Senior High School</p>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">School Name</label>
            <div class="form-field-value">${safe(o.seniorHighSchool)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Year Graduated</label>
            <div class="form-field-value">${safe(o.seniorHighYear)}</div>
          </div>
        </div>
        ${(isTransferee || hasCollegeData) ? `
        <p class="form-subsection-label">College (last school attended before transfer)</p>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">School Name</label>
            <div class="form-field-value">${safe(o.collegeSchool)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Year / Last Year Attended</label>
            <div class="form-field-value">${safe(o.collegeYear)}</div>
          </div>
        </div>
        ` : ''}
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION V — COURSE PREFERENCE & GWA</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">1st Course Choice</label>
            <div class="form-field-value" style="font-size:12px">${shortProg(dispFirst || '')}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">2nd Course Choice</label>
            <div class="form-field-value" style="font-size:12px">${shortProg(dispSecond || '')}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Grade 11 GWA</label>
            <div class="form-field-value">${dispG11}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Grade 12 GWA (1st Sem)</label>
            <div class="form-field-value">${dispG12}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Average GWA</label>
            <div class="form-field-value" style="color:var(--navy-mid);font-weight:700">${avgGWA(appForGwa)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Academic Standing</label>
            <div class="form-field-value">${(function () { var v = avgGWA(appForGwa); var n = parseFloat(v); return isNaN(n) ? '—' : n >= 90 ? 'With Honors' : n >= 85 ? 'Good Standing' : 'Regular'; })()}</div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">SECTION VI — APPLICANT CLASSIFICATION</div>
        <div class="form-grid-3">
          <div class="form-field">
            <label class="form-field-label">Person with Disability (PWD)</label>
            <div class="form-field-value">
              <span class="form-checkbox">${dispPwd === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${dispPwd === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Solo Parent</label>
            <div class="form-field-value">
              <span class="form-checkbox">${dispSolo === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${dispSolo === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Indigenous Person / Tribe Member</label>
            <div class="form-field-value">
              <span class="form-checkbox">${dispIndigenous === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${dispIndigenous === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">4Ps Beneficiary</label>
            <div class="form-field-value">
              <span class="form-checkbox">${dispFours === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${dispFours === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title">APPLICATION STATUS</div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:12px">
          <span class="badge ${statusClass(dispStatus)}" id="slideoverBadge" style="font-size:13px;padding:6px 14px;font-weight:600">${safe(dispStatus)}</span>
         
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
          <button type="button" class="btn-approve-reject btn-approve" data-app-id="${app.id != null ? app.id : ''}" data-status="Approved">Approve</button>
          <button type="button" class="btn-approve-reject btn-reject" data-app-id="${app.id != null ? app.id : ''}" data-status="Rejected">Reject</button>
        </div>
      </div>
    </div>
  `;

      var slideoverBody = document.getElementById('slideoverBody');
      var statusSelect = slideoverBody && slideoverBody.querySelector('.form-status-select-app');
      if (statusSelect) {
        statusSelect.addEventListener('change', function () {
          var newStatus = this.value;
          changeStatusById(app.id, newStatus);
          var badge = document.getElementById('slideoverBadge');
          if (badge) { badge.className = 'badge ' + statusClass(newStatus); badge.textContent = newStatus; }
        });
      }
      if (slideoverBody) slideoverBody.querySelectorAll('.btn-approve-reject').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var appId = Number(this.getAttribute('data-app-id'));
          var newStatus = this.getAttribute('data-status');
          if (appId && newStatus) {
            changeStatusById(appId, newStatus);
            var badge = document.getElementById('slideoverBadge');
            if (badge) { badge.className = 'badge ' + statusClass(newStatus); badge.textContent = newStatus; }
            var sel = slideoverBody.querySelector('.form-status-select-app');
            if (sel) sel.value = newStatus;
          }
        });
      });

    } catch (err) {
      console.error('Slideover content error:', err);
      if (bodyEl) bodyEl.innerHTML = '<p class="empty-state">Unable to load details. Check console for details.</p>';
    }
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
    loadSlideoverPhoto(bodyEl, o);

  }

  if (app.id && typeof AdmissionAPI !== 'undefined' && typeof AdmissionAPI.getApplication === 'function') {
    AdmissionAPI.getApplication(app.id).then(function (detail) {
      renderWith(Object.assign({}, app, detail));
    }).catch(function (err) {
      renderWith(app);
      if (err && err.status === 403 && bodyEl) {
        var banner = document.createElement('div');
        banner.className = 'slideover-403-banner';
        banner.innerHTML = 'Access denied. Log in as <strong>admin</strong> or <strong>staff</strong> to view full details and documents.';
        bodyEl.insertBefore(banner, bodyEl.firstChild);
      }
    });
  } else {
    renderWith(app);
  }
}

/** Load 2x2 photo from application documents (id_photo) into the slide-over photo box */
function loadSlideoverPhoto(bodyEl, rawData) {
  if (!bodyEl || !rawData || !rawData.photoPath) return;
  var box = bodyEl.querySelector && bodyEl.querySelector('.form-photo-box');
  if (!box) return;

  var img = document.createElement('img');
  img.src = storageUrl(rawData.photoPath);
  img.alt = '2x2 Photo';
  img.setAttribute('class', 'form-photo-img');
  img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;border-radius:4px;';

  box.innerHTML = '';
  box.appendChild(img);
}

function closeSlideover() {
  document.getElementById('slideover').classList.remove('open');
  document.getElementById('slideoverOverlay').classList.remove('open');
}

/* ─── PROGRAMS TABLE ─── */
function renderProgramsTable() {
  // Calculate accurate counts directly from the applications list
  const countMap = {};
  const apps = getApplications();
  
  if (Array.isArray(apps)) {
    apps.forEach(a => {
      // Count by ID
      if (a.programId) {
        countMap[String(a.programId)] = (countMap[String(a.programId)] || 0) + 1;
      }
      // Also count by name/code as fallback
      if (a.firstChoice) {
        countMap[String(a.firstChoice).toLowerCase()] = (countMap[String(a.firstChoice).toLowerCase()] || 0) + 1;
      }
    });
  }

  let programs = getPrograms();
  if (programFilter !== 'All') {
    programs = programs.filter(p => (p.department || '').toLowerCase().includes(programFilter.toLowerCase()));
  }

  const tbody = document.getElementById('programsTableBody');
  if (!tbody) return;
  if (programs.length === 0) {
    const message = window.LAST_PROGRAMS_API_ERROR
      ? `Programs could not be loaded from the database: ${escapeHtml(window.LAST_PROGRAMS_API_ERROR)}`
      : 'No programs loaded. Data comes from the API.';
    tbody.innerHTML = `<tr><td colspan="7" class="empty-state">${message}</td></tr>`;
    updateProgramSaveAllState();
    return;
  }
  tbody.innerHTML = programs.map(p => {
    // Determine the most accurate count: check ID first, then Name, then Code, then fallback to API count
    const idCount = countMap[String(p.id)] || 0;
    const nameCount = countMap[String(p.name).toLowerCase()] || 0;
    const code = (p.code && String(p.code).trim()) ? String(p.code).trim() : shortProg(p.name || '');
    const codeCount = countMap[String(code).toLowerCase()] || 0;
    
    // Pick the highest found count (manually calculated) or fall back to the API-provided count
    const calculatedCount = Math.max(idCount, nameCount, codeCount);
    const count = calculatedCount > 0 ? calculatedCount : (p.applications_count || 0);

    const enabled = (programEnabled[p.name] !== undefined) ? !!programEnabled[p.name] : !!p.is_active;
    const dept = p.department || '—';
    const slotsLeftVal = p.slots_left != null ? Number(p.slots_left) : 0;
    const savedSlotsLeft = Number.isFinite(slotsLeftVal) ? Math.max(0, Math.min(3000, slotsLeftVal)) : 0;
    const slotsLeft = pendingProgramSlots[String(p.id)] != null ? pendingProgramSlots[String(p.id)] : savedSlotsLeft;
    const programCode = (p.code && String(p.code).trim()) ? String(p.code).trim() : shortProg(p.name || '');
    const dirtyStyle = pendingProgramSlots[String(p.id)] != null ? 'border-color:var(--gold);background:#fffbeb;' : '';
    const isFull = slotsLeft <= 0;
    const statusText = enabled ? (isFull ? 'Full' : 'Active') : 'Disabled';
    const statusClass = enabled ? (isFull ? 'full' : 'on') : 'off';
    const statusIcon = enabled ? (isFull ? 'info-circle' : 'check-circle') : 'x';

    return `<tr data-id="${p.id}">

      <td>
        <div style="font-weight:600;color:var(--navy)">${p.name}</div>
        <div style="font-size:12px;color:var(--text-3)">${escapeHtml(programCode)} ${isFull && enabled ? '<span style="color:var(--red);font-weight:700;">(FULL)</span>' : ''}</div>
      </td>
      <td><span class="badge" style="background:var(--navy-pale);color:var(--navy-mid)">${dept}</span></td>
      <td>${p.duration_years || 4} Years</td>
      <td>${p.schedule || 'Day / Evening'}</td>
      <td style="font-weight:700;color:var(--navy-mid);text-align:center;">${count}</td>
      <td>
        <div style="display:flex;align-items:center;gap:6px;min-width:96px">
          <input type="number" min="0" max="3000" step="1" class="fi program-slot-input"
            data-program-id="${p.id}" data-saved-value="${savedSlotsLeft}" value="${slotsLeft}"
            oninput="markProgramSlotChanged(this)" onchange="markProgramSlotChanged(this)"
            style="padding:5px 8px;font-size:12px;width:86px;height:30px;${dirtyStyle}">
        </div>
      </td>
      <td>
        <button class="prog-toggle ${statusClass}" onclick="toggleProgram('${String(p.id).replace(/'/g, "\\'")}', '${String(p.name).replace(/'/g, "\\'")}', this)">
          <i data-iconsax="${statusIcon}" style="width:13px;height:13px"></i>
          ${statusText}
        </button>
      </td>
    </tr>`;
  }).join('');
  updateProgramSaveAllState();
}

function markProgramSlotChanged(input) {
  if (!input) return;
  const programId = String(input.dataset.programId || '');
  const savedValue = Number(input.dataset.savedValue);
  const value = Number(input.value);

  if (!programId) return;
  if (Number.isInteger(value) && value === savedValue) {
    delete pendingProgramSlots[programId];
    input.style.borderColor = '';
    input.style.background = '';
  } else {
    pendingProgramSlots[programId] = input.value;
    input.style.borderColor = 'var(--gold)';
    input.style.background = '#fffbeb';
  }

  updateProgramSaveAllState();
}

function updateProgramSaveAllState() {
  const btn = document.getElementById('saveAllProgramSlotsBtn');
  if (!btn) return;
  const count = Object.keys(pendingProgramSlots).length;
  btn.disabled = count === 0;
  btn.classList.toggle('has-pending-changes', count > 0);
  
  // Use a simple text-based icon update to avoid expensive createIcons() on every keystroke
  const iconHtml = count ? '<span class="pulse-dot"></span>' : '<i data-iconsax="save"></i>';
  btn.innerHTML = `${iconHtml} ${count ? `Save All Changes (${count})` : 'Save All Changes'}`;
  
  // Only create icons if we are showing the default save icon
  if (!count && typeof iconsax !== 'undefined') iconsax.createIcons();
}

async function saveAllProgramSlotsLeft() {
  const changes = Object.entries(pendingProgramSlots).map(([programId, rawValue]) => ({
    programId,
    value: Number(rawValue)
  }));

  if (!changes.length) {
    showToast('No program changes to save.');
    return;
  }

  for (const change of changes) {
    if (!Number.isInteger(change.value) || change.value < 0 || change.value > 3000) {
      const input = document.querySelector(`.program-slot-input[data-program-id="${change.programId}"]`);
      showToast('Slots Left must be a whole number between 0 and 3000.');
      if (input) input.focus();
      return;
    }
  }

  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
    showToast('Please log in to update slots.');
    return;
  }

  const btn = document.getElementById('saveAllProgramSlotsBtn');
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<i data-iconsax="loader"></i> Saving...';
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }

  const results = await Promise.allSettled(
    changes.map(change => AdmissionAPI.updateProgramSlotsLeft(change.programId, change.value))
  );
  const failed = results.filter(result => result.status === 'rejected');

  results.forEach((result, index) => {
    if (result.status !== 'fulfilled') return;
    const { programId, value } = changes[index];
    const program = API_PROGRAMS.find(p => Number(p.id) === Number(programId));
    if (program) {
      program.slots_left = value;
      // Backend auto-deactivates if slots are 0, so sync local state
      if (value <= 0) {
        program.is_active = false;
        programEnabled[program.name] = false;
      }
    }
    delete pendingProgramSlots[String(programId)];
  });

  if (failed.length) {
    const savedCount = changes.length - failed.length;
    const err = failed[0].reason;
    let msg = err && err.message ? err.message : 'Some program changes failed to save.';
    if (err && err.data && err.data.errors && err.data.errors.slots_left && err.data.errors.slots_left[0]) {
      msg = err.data.errors.slots_left[0];
    }
    showToast(`${savedCount} saved, ${failed.length} failed. ${msg}`);
  } else {
    showToast('All program changes saved.');
  }

  renderProgramsTable();

  if (btn) {
    btn.disabled = false;
    btn.innerHTML = '<i data-iconsax="save"></i> Save All Changes';
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }
}



/* ─── WEBSITE CONTENT & ANNOUNCEMENTS ─── */
let API_ANNOUNCEMENTS = [];
let API_NEWS_EVENTS = [];
let API_TESTIMONIALS = [];
let API_FACULTY_STAFF = [];
let announcementPopupImageRemoved = false;
let facultyStaffImageFile = null;
let facultyStaffExistingImage = "";
let facultyStaffImageRemoved = false;
const CONTENT_TABLE_PAGE_SIZE = 10;
const CONTENT_TABLE_PAGES = {
  announcements: 1,
  newsEvents: 1,
  testimonials: 1,
  facultyStaff: 1,
};

function getContentPageItems(items, key) {
  const totalPages = Math.max(1, Math.ceil(items.length / CONTENT_TABLE_PAGE_SIZE));
  CONTENT_TABLE_PAGES[key] = Math.min(Math.max(CONTENT_TABLE_PAGES[key] || 1, 1), totalPages);
  const start = (CONTENT_TABLE_PAGES[key] - 1) * CONTENT_TABLE_PAGE_SIZE;
  return items.slice(start, start + CONTENT_TABLE_PAGE_SIZE);
}

function clearContentPagination(key) {
  const pager = document.getElementById(`${key}Pagination`);
  if (!pager) return;
  pager.innerHTML = "";
  pager.style.display = "none";
}

function renderContentPagination(key, totalItems) {
  const pager = document.getElementById(`${key}Pagination`);
  if (!pager) return;

  if (totalItems <= CONTENT_TABLE_PAGE_SIZE) {
    clearContentPagination(key);
    return;
  }

  const totalPages = Math.ceil(totalItems / CONTENT_TABLE_PAGE_SIZE);
  const currentPage = Math.min(Math.max(CONTENT_TABLE_PAGES[key] || 1, 1), totalPages);
  const start = (currentPage - 1) * CONTENT_TABLE_PAGE_SIZE + 1;
  const end = Math.min(currentPage * CONTENT_TABLE_PAGE_SIZE, totalItems);

  pager.style.display = "flex";
  pager.innerHTML = `
    <span style="font-size:12px;color:var(--text-3);font-weight:600;">Showing ${start}-${end} of ${totalItems}</span>
    <div style="display:flex;align-items:center;gap:8px;">
      <button type="button" class="btn-outline" style="padding:6px 10px;font-size:12px;" onclick="setContentTablePage('${key}', ${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}>Previous</button>
      <span style="font-size:12px;color:var(--text-2);font-weight:700;">Page ${currentPage} of ${totalPages}</span>
      <button type="button" class="btn-outline" style="padding:6px 10px;font-size:12px;" onclick="setContentTablePage('${key}', ${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}>Next</button>
    </div>
  `;
}

function setContentTablePage(key, page) {
  CONTENT_TABLE_PAGES[key] = page;
  if (key === "announcements") renderAnnouncementsTable();
  if (key === "newsEvents") renderNewsEventsTable();
  if (key === "testimonials") renderTestimonialsTable();
  if (key === "facultyStaff") renderFacultyStaffTable();
}

function initWebsiteContent() {
  loadWebsiteSettings();
  loadAnnouncements();
  loadNewsEvents();
  loadTestimonials();
  loadFacultyStaff();
  initContentStudioSidebar();

  // Wire up save button
  const saveBtn = document.getElementById("saveWebsiteSettingsBtn");
  if (saveBtn) {
    saveBtn.onclick = saveWebsiteSettings;
  }


  // Wire up announcement modal popup toggle
  const popupCheck = document.getElementById("annIsPopup");
  if (popupCheck) {
    popupCheck.onchange = function () {
      document.getElementById("popupFields").style.display = this.checked ? "block" : "none";
    };
  }

  // Save announcement button
  const saveAnnBtn = document.getElementById("saveAnnouncementBtn");
  if (saveAnnBtn) {
    saveAnnBtn.onclick = saveAnnouncement;
  }

  const annPopupImageFile = document.getElementById("annPopupImageFile");
  if (annPopupImageFile && !annPopupImageFile.dataset.bound) {
    annPopupImageFile.addEventListener("change", function () {
      const file = this.files && this.files[0] ? this.files[0] : null;
      if (!file) return;

      const error = validateAnnouncementImage(file);
      if (error) {
        this.value = "";
        setAnnouncementImageError(error);
        showToast(error);
        return;
      }

      announcementPopupImageRemoved = false;
      setAnnouncementImageError("");
      showAnnouncementImagePreview(URL.createObjectURL(file));
    });
    annPopupImageFile.dataset.bound = "1";
  }

  const annPopupImageRemoveBtn = document.getElementById("annPopupImageRemoveBtn");
  if (annPopupImageRemoveBtn && !annPopupImageRemoveBtn.dataset.bound) {
    annPopupImageRemoveBtn.addEventListener("click", function () {
      const fileInput = document.getElementById("annPopupImageFile");
      if (fileInput) fileInput.value = "";
      announcementPopupImageRemoved = true;
      hideAnnouncementImagePreview();
      setAnnouncementImageError("");
    });
    annPopupImageRemoveBtn.dataset.bound = "1";
  }

  const saveNewsBtn = document.getElementById("saveNewsEventBtn");
  if (saveNewsBtn) {
    saveNewsBtn.onclick = saveNewsEvent;
  }

  const saveTestimonialBtn = document.getElementById("saveTestimonialBtn");
  if (saveTestimonialBtn) {
    saveTestimonialBtn.onclick = saveTestimonial;
  }

  const saveFacultyStaffBtn = document.getElementById("saveFacultyStaffBtn");
  if (saveFacultyStaffBtn) {
    saveFacultyStaffBtn.onclick = saveFacultyStaff;
  }
}

function validateAnnouncementImage(file) {
  if (!file) return "";
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
  const maxBytes = 2 * 1024 * 1024;

  if (!allowedTypes.includes(file.type)) {
    return "Invalid file type. Please upload JPG, PNG, or WEBP.";
  }
  if (file.size > maxBytes) {
    return "File is too large. Maximum allowed size is 2MB.";
  }
  return "";
}

function setAnnouncementImageError(message) {
  const errorEl = document.getElementById("annPopupImageError");
  if (!errorEl) return;
  errorEl.textContent = message || "";
  errorEl.style.display = message ? "block" : "none";
}

function showAnnouncementImagePreview(src) {
  const preview = document.getElementById("annPopupImagePreview");
  const img = document.getElementById("annPopupImagePreviewImg");
  if (!preview || !img || !src) return;
  img.src = src;
  preview.style.display = "block";
}

function hideAnnouncementImagePreview() {
  const preview = document.getElementById("annPopupImagePreview");
  const img = document.getElementById("annPopupImagePreviewImg");
  if (img) img.src = "";
  if (preview) preview.style.display = "none";
}

function initContentStudioSidebar() {
  const navItems = document.querySelectorAll(".studio-nav-item");
  const panels = document.querySelectorAll(".studio-panel");
  if (!navItems.length || !panels.length) return;

  navItems.forEach(btn => {
    if (btn.dataset.bound === "1") return;
    btn.addEventListener("click", () => {
      const targetId = btn.getAttribute("data-target");
      navItems.forEach(item => item.classList.remove("active"));
      panels.forEach(panel => panel.classList.remove("active"));

      btn.classList.add("active");
      const targetPanel = document.getElementById(targetId);
      if (targetPanel) targetPanel.classList.add("active");
    });
    btn.dataset.bound = "1";
  });
}

function loadWebsiteSettings() {
  if (!SYSTEM_SETTINGS) return;

  const fields = {
    settingHeroHeadline: "hero_headline",
    settingHeroSubheadline: "hero_subheadline",
    settingSYLabel: "school_year_label",
    settingCTAText: "cta_text",
    settingContactPhone: "contact_phone",
    settingOfficeHours: "contact_office_hours",
    settingFacebook: "facebook_link",
    settingInstagram: "instagram_link"
  };

  for (const [id, key] of Object.entries(fields)) {
    const el = document.getElementById(id);
    if (el) el.value = SYSTEM_SETTINGS[key] || "";
  }
}

async function saveWebsiteSettings() {
  const fields = {
    hero_headline: "settingHeroHeadline",
    hero_subheadline: "settingHeroSubheadline",
    school_year_label: "settingSYLabel",
    cta_text: "settingCTAText",
    contact_phone: "settingContactPhone",
    contact_office_hours: "settingOfficeHours",
    facebook_link: "settingFacebook",
    instagram_link: "settingInstagram"
  };

  const data = {};
  for (const [key, id] of Object.entries(fields)) {
    const el = document.getElementById(id);
    if (el) data[key] = el.value;
  }

  const btn = document.getElementById("saveWebsiteSettingsBtn");
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = "Saving...";

  try {
    if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
      throw new Error('Not authenticated. Please log in.');
    }
    const updated = await AdmissionAPI.saveSettings(data);
    SYSTEM_SETTINGS = Object.assign(SYSTEM_SETTINGS || {}, updated);
    showToast("Website settings saved successfully.");
  } catch (err) {
    showToast('Failed to save settings: ' + (err.message || 'Unknown error'));
    console.error('Save website settings error:', err);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalText;
  }
}

async function loadAnnouncements() {
  const tbody = document.getElementById("announcementsTableBody");
  if (tbody) tbody.innerHTML = "<tr><td colspan=\"4\">Loading...</td></tr>";
  clearContentPagination("announcements");

  try {
    const res = await apiFetch("/api/announcements", {
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      }
    });
    const result = await res.json();
    API_ANNOUNCEMENTS = result.data || [];
    renderAnnouncementsTable();
  } catch (err) {
    console.error("Failed to load announcements:", err);
    if (tbody) tbody.innerHTML = "<tr><td colspan=\"4\" style=\"color:var(--red)\">Failed to load.</td></tr>";
  }
}

function renderAnnouncementsTable() {
  const tbody = document.getElementById("announcementsTableBody");
  if (!tbody) return;

  if (API_ANNOUNCEMENTS.length === 0) {
    tbody.innerHTML = "<tr><td colspan=\"4\"><p class=\"empty-state\">No announcements yet.</p></td></tr>";
    clearContentPagination("announcements");
    return;
  }

  const pageItems = getContentPageItems(API_ANNOUNCEMENTS, "announcements");

  tbody.innerHTML = pageItems.map(ann => `
    <tr>
      <td>
        <div style=\"max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:500;\" title=\"${escapeHtml(ann.message)}\">
          ${escapeHtml(ann.message)}
        </div>
      </td>
      <td>
        <span class=\"badge ${ann.is_popup ? "badge--interview" : "badge--pending"}\">
          ${ann.is_popup ? "Popup" : "Ticker"}
        </span>
      </td>
      <td>
        <span class=\"badge ${ann.is_active ? "badge--approved" : "badge--rejected"}\">
          ${ann.is_active ? "Active" : "Inactive"}
        </span>
      </td>
      <td>
        <div style="display:flex; gap:8px;">
          <button class="btn-view-label" onclick="openAnnouncementModal(${ann.id})">Edit</button>
          <button class="btn-view-label" style="background:#fee2e2; color:#b91c1c;" onclick="deleteAnnouncement(${ann.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join("");
  renderContentPagination("announcements", API_ANNOUNCEMENTS.length);
}

function openAnnouncementModal(id = null) {
  const modal = document.getElementById("announcementModal");
  const title = document.getElementById("announcementModalTitle");
  const idInput = document.getElementById("editAnnouncementId");

  // Clear fields
  idInput.value = id || "";
  document.getElementById("annMessage").value = "";
  document.getElementById("annStartsAt").value = "";
  document.getElementById("annEndsAt").value = "";
  document.getElementById("annIsPopup").checked = false;
  document.getElementById("annIsActive").checked = true;
  document.getElementById("annPopupButtonText").value = "";
  document.getElementById("annPopupButtonLink").value = "";
  document.getElementById("popupFields").style.display = "none";
  const popupImageFile = document.getElementById("annPopupImageFile");
  if (popupImageFile) popupImageFile.value = "";
  announcementPopupImageRemoved = false;
  setAnnouncementImageError("");
  hideAnnouncementImagePreview();

  if (id) {
    title.textContent = "Edit Announcement";
    const ann = API_ANNOUNCEMENTS.find(a => a.id === id);
    if (ann) {
      document.getElementById("annMessage").value = ann.message || "";
      if (ann.starts_at) document.getElementById("annStartsAt").value = ann.starts_at.split("T")[0];
      if (ann.ends_at) document.getElementById("annEndsAt").value = ann.ends_at.split("T")[0];
      document.getElementById("annIsPopup").checked = !!ann.is_popup;
      document.getElementById("annIsActive").checked = !!ann.is_active;
      document.getElementById("annPopupButtonText").value = ann.popup_button_text || "";
      document.getElementById("annPopupButtonLink").value = ann.popup_button_link || "";
      if (ann.popup_image) showAnnouncementImagePreview(ann.popup_image);
      if (ann.is_popup) document.getElementById("popupFields").style.display = "block";
    }
  } else {
    title.textContent = "Add Announcement";
  }

  modal.style.display = "flex";
}

function closeAnnouncementModal() {
  document.getElementById("announcementModal").style.display = "none";
}

async function saveAnnouncement() {
  const id = document.getElementById("editAnnouncementId").value;
  const data = {
    message: document.getElementById("annMessage").value,
    starts_at: document.getElementById("annStartsAt").value || "",
    ends_at: document.getElementById("annEndsAt").value || "",
    is_popup: document.getElementById("annIsPopup").checked ? "1" : "0",
    is_active: document.getElementById("annIsActive").checked ? "1" : "0",
    popup_button_text: document.getElementById("annPopupButtonText").value,
    popup_button_link: document.getElementById("annPopupButtonLink").value,
  };
  const popupImageFile = document.getElementById("annPopupImageFile");
  const selectedImage = popupImageFile && popupImageFile.files && popupImageFile.files[0] ? popupImageFile.files[0] : null;
  const imageError = validateAnnouncementImage(selectedImage);

  if (!data.message) {
    showToast("Message is required");
    return;
  }

  if (imageError) {
    setAnnouncementImageError(imageError);
    showToast(imageError);
    return;
  }

  const btn = document.getElementById("saveAnnouncementBtn");
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i data-iconsax="loader" class="animate-spin" style="width:14px;height:14px;margin-right:8px;"></i> Saving...';

  try {
    const url = id ? `/api/announcements/${id}` : "/api/announcements";
    const method = "POST";
    const payload = new FormData();

    Object.entries(data).forEach(([key, value]) => {
      payload.append(key, value);
    });

    if (selectedImage) {
      payload.append("popup_image_file", selectedImage);
    } else if (announcementPopupImageRemoved) {
      payload.append("clear_popup_image", "1");
    }

    if (id) {
      payload.append("_method", "PATCH");
    }

    const res = await apiFetch(url, {
      method,
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      },
      body: payload
    });

    if (res.ok) {
      showToast(id ? "Announcement updated" : "Announcement created");
      closeAnnouncementModal();
      reloadContentSoon(loadAnnouncements);
    } else {
      const err = await res.json();
      throw new Error(err.message || "Failed to save announcement");
    }
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
}

async function deleteAnnouncement(id) {
  showConfirmModal({
    title: "Delete Announcement?",
    message: "Are you sure you want to delete this announcement? This action cannot be undone.",
    confirmText: "Delete",
    onConfirm: async () => {
      try {
        const res = await apiFetch(`/api/announcements/${id}`, {
          method: "DELETE",
          headers: {
            "Authorization": "Bearer " + sessionStorage.getItem("_at"),
            "Accept": "application/json"
          }
        });

        if (res.ok) {
          showToast("Announcement deleted");
          reloadContentSoon(loadAnnouncements);
        } else {
          throw new Error("Failed to delete");
        }
      } catch (err) {
        showToast(err.message);
      }
    }
  });
}

async function loadNewsEvents() {
  const tbody = document.getElementById("newsEventsTableBody");
  if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\">Loading...</td></tr>";
  clearContentPagination("newsEvents");

  try {
    const res = await apiFetch("/api/admin/news-events", {
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      }
    });
    const result = await res.json();
    API_NEWS_EVENTS = result.data || [];
    renderNewsEventsTable();
  } catch (err) {
    console.error("Failed to load news/events:", err);
    if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\" style=\"color:var(--red)\">Failed to load.</td></tr>";
  }
}

let newsEventWorkingImages = [];
let draggedNewsImageIndex = null;

function moveNewsImageToCover(index) {
  if (index < 0 || index >= newsEventWorkingImages.length) return;
  const selected = newsEventWorkingImages.splice(index, 1)[0];
  newsEventWorkingImages.unshift(selected);
  refreshNewsImagePreviewGrid();
}

function removeNewsImageAt(index) {
  if (index < 0 || index >= newsEventWorkingImages.length) return;
  newsEventWorkingImages.splice(index, 1);
  refreshNewsImagePreviewGrid();
}

function refreshNewsImagePreviewGrid() {
  const imagePreviewGrid = document.getElementById("newsEventImagePreviewGrid");
  if (!imagePreviewGrid) return;

  imagePreviewGrid.innerHTML = "";
  newsEventWorkingImages.forEach((img, index) => {
    const wrapper = document.createElement("div");
    wrapper.draggable = true;
    wrapper.dataset.index = String(index);
    wrapper.style.position = "relative";
    wrapper.style.cursor = "grab";

    const imageEl = document.createElement("img");
    imageEl.src = img.type === "file" ? URL.createObjectURL(img.file) : img.url;
    imageEl.alt = img.type === "file" ? (img.file?.name || "Preview") : "Existing image";
    imageEl.style.width = "100%";
    imageEl.style.height = "92px";
    imageEl.style.objectFit = "cover";
    imageEl.style.borderRadius = "8px";
    imageEl.style.border = index === 0 ? "2px solid #1d4ed8" : "1px solid #e2e8f0";

    const badge = document.createElement("span");
    badge.textContent = index === 0 ? "Cover" : `${index + 1}`;
    badge.style.position = "absolute";
    badge.style.top = "6px";
    badge.style.left = "6px";
    badge.style.background = index === 0 ? "#1d4ed8" : "rgba(15, 23, 42, 0.65)";
    badge.style.color = "#fff";
    badge.style.fontSize = "10px";
    badge.style.fontWeight = "700";
    badge.style.padding = "2px 6px";
    badge.style.borderRadius = "999px";

    const actions = document.createElement("div");
    actions.style.position = "absolute";
    actions.style.bottom = "6px";
    actions.style.right = "6px";
    actions.style.display = "flex";
    actions.style.gap = "6px";

    const coverBtn = document.createElement("button");
    coverBtn.type = "button";
    coverBtn.textContent = "Set Cover";
    coverBtn.style.fontSize = "10px";
    coverBtn.style.fontWeight = "700";
    coverBtn.style.padding = "2px 6px";
    coverBtn.style.borderRadius = "999px";
    coverBtn.style.border = "1px solid rgba(255,255,255,.65)";
    coverBtn.style.background = "rgba(15,30,61,.82)";
    coverBtn.style.color = "#fff";
    coverBtn.style.cursor = "pointer";
    coverBtn.style.display = index === 0 ? "none" : "inline-flex";
    coverBtn.addEventListener("click", (e) => {
      e.preventDefault();
      moveNewsImageToCover(index);
    });

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.textContent = "Remove";
    removeBtn.style.fontSize = "10px";
    removeBtn.style.fontWeight = "700";
    removeBtn.style.padding = "2px 6px";
    removeBtn.style.borderRadius = "999px";
    removeBtn.style.border = "1px solid rgba(255,255,255,.65)";
    removeBtn.style.background = "rgba(185,28,28,.88)";
    removeBtn.style.color = "#fff";
    removeBtn.style.cursor = "pointer";
    removeBtn.addEventListener("click", (e) => {
      e.preventDefault();
      removeNewsImageAt(index);
    });

    actions.appendChild(coverBtn);
    actions.appendChild(removeBtn);

    wrapper.appendChild(imageEl);
    wrapper.appendChild(badge);
    wrapper.appendChild(actions);

    wrapper.addEventListener("dragstart", () => {
      draggedNewsImageIndex = index;
    });
    wrapper.addEventListener("dragover", (e) => {
      e.preventDefault();
    });
    wrapper.addEventListener("drop", (e) => {
      e.preventDefault();
      const dropIndex = Number(wrapper.dataset.index);
      if (draggedNewsImageIndex === null || dropIndex === draggedNewsImageIndex) return;
      const moved = newsEventWorkingImages.splice(draggedNewsImageIndex, 1)[0];
      newsEventWorkingImages.splice(dropIndex, 0, moved);
      draggedNewsImageIndex = null;
      refreshNewsImagePreviewGrid();
    });

    imagePreviewGrid.appendChild(wrapper);
  });

  imagePreviewGrid.style.display = newsEventWorkingImages.length ? "grid" : "none";
}

function renderNewsEventsTable() {
  const tbody = document.getElementById("newsEventsTableBody");
  if (!tbody) return;

  if (API_NEWS_EVENTS.length === 0) {
    tbody.innerHTML = "<tr><td colspan=\"5\"><p class=\"empty-state\">No news/events yet.</p></td></tr>";
    clearContentPagination("newsEvents");
    return;
  }

  const pageItems = getContentPageItems(API_NEWS_EVENTS, "newsEvents");

  tbody.innerHTML = pageItems.map(item => `
    <tr>
      <td>
        <div style="max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:500;" title="${escapeHtml(item.title)}">
          ${escapeHtml(item.title)}
        </div>
      </td>
      <td>
        <span class="badge ${item.type === 'event' ? "badge--interview" : "badge--pending"}">
          ${item.type === 'event' ? "Event" : "News"}
        </span>
      </td>
      <td>${item.event_date ? formatDate(item.event_date) : "—"}</td>
      <td>
        <span class="badge ${item.is_active ? "badge--approved" : "badge--rejected"}">
          ${item.is_active ? "Active" : "Inactive"}
        </span>
      </td>
      <td>
        <div style="display:flex; gap:8px;">
          <button class="btn-view-label" onclick="openNewsEventModal(${item.id})">Edit</button>
          <button class="btn-view-label" style="background:#fee2e2; color:#b91c1c;" onclick="deleteNewsEvent(${item.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join("");
  renderContentPagination("newsEvents", API_NEWS_EVENTS.length);
}

function openNewsEventModal(id = null) {
  const modal = document.getElementById("newsEventModal");
  const title = document.getElementById("newsEventModalTitle");
  const idInput = document.getElementById("editNewsEventId");
  const imageFile = document.getElementById("newsEventImageFile");
  const imagePreviewGrid = document.getElementById("newsEventImagePreviewGrid");
  const dropzone = document.getElementById("newsEventDropzone");
  const imageError = document.getElementById("newsEventImageError");

  idInput.value = id || "";
  document.getElementById("newsEventTitle").value = "";
  document.getElementById("newsEventType").value = "news";
  document.getElementById("newsEventDate").value = "";
  document.getElementById("newsEventLocation").value = "";
  document.getElementById("newsEventSummary").value = "";
  document.getElementById("newsEventContent").value = "";
  document.getElementById("newsEventIsActive").checked = true;
  newsEventWorkingImages = [];
  if (imageFile) imageFile.value = "";
  if (imagePreviewGrid) {
    imagePreviewGrid.innerHTML = "";
    imagePreviewGrid.style.display = "none";
  }
  if (imageError) {
    imageError.textContent = "";
    imageError.style.display = "none";
  }

  function validateImage(file) {
    if (!file) return { ok: true };
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    const maxBytes = 2 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {
      return { ok: false, message: "Invalid file type. Please upload JPG, PNG, or WEBP." };
    }
    if (file.size > maxBytes) {
      return { ok: false, message: "File is too large. Maximum allowed size is 2MB." };
    }
    return { ok: true };
  }

  function setImageError(message) {
    if (!imageError) return;
    imageError.textContent = message || "";
    imageError.style.display = message ? "block" : "none";
  }

  function renderPreviewImages(files, existingUrls = []) {
    if (Array.isArray(files) && files.length) {
      newsEventWorkingImages = files.map((file) => ({ type: "file", file }));
    } else if (Array.isArray(existingUrls) && existingUrls.length) {
      newsEventWorkingImages = existingUrls.map((url) => ({ type: "existing", url }));
    } else if (!newsEventWorkingImages.length) {
      newsEventWorkingImages = [];
    }
    refreshNewsImagePreviewGrid();
  }

  function applySelectedImages(filesList) {
    const files = Array.from(filesList || []);
    if (!files.length) return;

    for (const file of files) {
      const check = validateImage(file);
      if (!check.ok) {
        if (imageFile) imageFile.value = "";
        if (imagePreviewGrid) {
          imagePreviewGrid.innerHTML = "";
          imagePreviewGrid.style.display = "none";
        }
        setImageError(check.message);
        showToast(check.message);
        return;
      }
    }

    setImageError("");
    newsEventWorkingImages = newsEventWorkingImages.concat(files.map((file) => ({ type: "file", file })));
    refreshNewsImagePreviewGrid();
  }

  if (imageFile && !imageFile.dataset.bound) {
    imageFile.addEventListener("change", function () {
      applySelectedImages(this.files);
    });
    imageFile.dataset.bound = "1";
  }

  if (dropzone && !dropzone.dataset.bound) {
    dropzone.addEventListener("click", () => {
      if (imageFile) imageFile.click();
    });

    dropzone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#0f1e3d";
      dropzone.style.background = "#eff6ff";
    });

    dropzone.addEventListener("dragleave", () => {
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";
    });

    dropzone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";

      const files = e.dataTransfer && e.dataTransfer.files ? Array.from(e.dataTransfer.files) : [];
      if (!files.length) return;

      if (imageFile) {
        const transfer = new DataTransfer();
        files.forEach((file) => transfer.items.add(file));
        imageFile.files = transfer.files;
      }
      applySelectedImages(files);
    });

    dropzone.dataset.bound = "1";
  }

  if (id) {
    title.textContent = "Edit News/Event";
    const item = API_NEWS_EVENTS.find(n => n.id === id);
    if (item) {
      document.getElementById("newsEventTitle").value = item.title || "";
      document.getElementById("newsEventType").value = item.type || "news";
      if (item.event_date) document.getElementById("newsEventDate").value = item.event_date.split("T")[0];
      document.getElementById("newsEventLocation").value = item.location || "";
      document.getElementById("newsEventSummary").value = item.summary || "";
      document.getElementById("newsEventContent").value = item.content || "";
      document.getElementById("newsEventIsActive").checked = !!item.is_active;
      const existingImages = Array.isArray(item.image_urls) && item.image_urls.length
        ? item.image_urls
        : (item.image_url ? [item.image_url] : []);
      renderPreviewImages([], existingImages);
    }
  } else {
    title.textContent = "Add News/Event";
    newsEventWorkingImages = [];
  }

  modal.style.display = "flex";
}

function closeNewsEventModal() {
  const modal = document.getElementById("newsEventModal");
  if (modal) modal.style.display = "none";
}

async function saveNewsEvent() {
  const id = document.getElementById("editNewsEventId").value;
  const data = {
    title: document.getElementById("newsEventTitle").value,
    type: document.getElementById("newsEventType").value,
    event_date: document.getElementById("newsEventDate").value || "",
    location: document.getElementById("newsEventLocation").value || "",
    summary: document.getElementById("newsEventSummary").value || "",
    content: document.getElementById("newsEventContent").value || "",
    is_active: document.getElementById("newsEventIsActive").checked ? "1" : "0",
  };
  const imageError = document.getElementById("newsEventImageError");
  const fileImageEntries = newsEventWorkingImages.filter((img) => img.type === "file");
  const selectedFiles = fileImageEntries.map((img) => img.file);
  const existingUrls = newsEventWorkingImages.filter((img) => img.type === "existing").map((img) => img.url);
  const imageItems = newsEventWorkingImages.map((img) => {
    if (img.type === "existing") return { type: "existing", url: img.url };
    return { type: "file", index: fileImageEntries.indexOf(img) };
  });

  if (selectedFiles.length) {
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    const maxBytes = 2 * 1024 * 1024;
    for (const file of selectedFiles) {
      if (!allowedTypes.includes(file.type)) {
        if (imageError) {
          imageError.textContent = "Invalid file type. Please upload JPG, PNG, or WEBP.";
          imageError.style.display = "block";
        }
        showToast("Invalid image type.");
        return;
      }
      if (file.size > maxBytes) {
        if (imageError) {
          imageError.textContent = "File is too large. Maximum allowed size is 2MB.";
          imageError.style.display = "block";
        }
        showToast("Image exceeds 2MB.");
        return;
      }
    }
  }

  if (!data.title) {
    showToast("Title is required");
    return;
  }

  const btn = document.getElementById("saveNewsEventBtn");
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i data-iconsax="loader" class="animate-spin" style="width:14px;height:14px;margin-right:8px;"></i> Saving...';

  try {
    const url = id ? `/api/admin/news-events/${id}` : "/api/admin/news-events";
    const method = id ? "POST" : "POST";
    const payload = new FormData();

    Object.entries(data).forEach(([key, value]) => {
      payload.append(key, value);
    });

    if (existingUrls.length) {
      // Persist ordering for existing images (no new upload)
      payload.append("image_urls_json", JSON.stringify(existingUrls));
    }

    if (selectedFiles.length) {
      selectedFiles.forEach((file) => payload.append("images[]", file));
      payload.append("image_items_json", JSON.stringify(imageItems));
    } else if (!existingUrls.length && id) {
      // Editing: user removed all images
      payload.append("clear_images", "1");
    }

    if (id) {
      payload.append("_method", "PATCH");
    }

    const res = await apiFetch(url, {
      method,
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      },
      body: payload
    });

    if (res.ok) {
      showToast(id ? "News/Event updated" : "News/Event created");
      closeNewsEventModal();
      reloadContentSoon(loadNewsEvents);
    } else {
      const err = await res.json();
      throw new Error(err.message || "Failed to save News/Event");
    }
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
}

async function deleteNewsEvent(id) {
  showConfirmModal({
    title: "Delete News/Event?",
    message: "Are you sure you want to delete this item? This action will permanently remove it from the website.",
    confirmText: "Delete",
    onConfirm: async () => {
      try {
        const res = await apiFetch(`/api/admin/news-events/${id}`, {
          method: "DELETE",
          headers: {
            "Authorization": "Bearer " + sessionStorage.getItem("_at"),
            "Accept": "application/json"
          }
        });

        if (res.ok) {
          showToast("News/Event deleted");
          reloadContentSoon(loadNewsEvents);
        } else {
          throw new Error("Failed to delete");
        }
      } catch (err) {
        showToast(err.message);
      }
    }
  });
}

async function loadTestimonials() {
  const tbody = document.getElementById("testimonialsTableBody");
  if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\">Loading...</td></tr>";
  clearContentPagination("testimonials");

  try {
    const res = await apiFetch("/api/testimonials", {
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      }
    });
    const result = await res.json();
    API_TESTIMONIALS = result.data || [];
    renderTestimonialsTable();
  } catch (err) {
    console.error("Failed to load testimonials:", err);
    if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\" style=\"color:var(--red)\">Failed to load.</td></tr>";
  }
}

function renderTestimonialsTable() {
  const tbody = document.getElementById("testimonialsTableBody");
  if (!tbody) return;

  if (!API_TESTIMONIALS.length) {
    tbody.innerHTML = "<tr><td colspan=\"5\"><p class=\"empty-state\">No testimonials yet.</p></td></tr>";
    clearContentPagination("testimonials");
    return;
  }

  const pageItems = getContentPageItems(API_TESTIMONIALS, "testimonials");

  tbody.innerHTML = pageItems.map(item => `
    <tr>
      <td>
        <div style="font-weight:600;">${escapeHtml(item.author_name)}</div>
        <div style="font-size:12px;color:var(--text-3);max-width:190px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escapeHtml(item.author_role)}">${escapeHtml(item.author_role)}</div>
      </td>
      <td>
        <div style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escapeHtml(item.message)}">
          ${escapeHtml(item.message)}
        </div>
      </td>
      <td>${item.order != null ? item.order : 0}</td>
      <td>
        <span class="badge ${item.is_active ? "badge--approved" : "badge--rejected"}">
          ${item.is_active ? "Active" : "Inactive"}
        </span>
      </td>
      <td>
        <div style="display:flex; gap:8px;">
          <button class="btn-view-label" onclick="openTestimonialModal(${item.id})">Edit</button>
          <button class="btn-view-label" style="background:#fee2e2; color:#b91c1c;" onclick="deleteTestimonial(${item.id})">Delete</button>
        </div>
      </td>
    </tr>
  `).join("");
  renderContentPagination("testimonials", API_TESTIMONIALS.length);
}

let testimonialAvatarFile = null;
let testimonialExistingAvatar = "";
let testimonialAvatarRemoved = false;

function validateTestimonialAvatar(file) {
  if (!file) return { ok: true };
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
  const maxBytes = 2 * 1024 * 1024;

  if (!allowedTypes.includes(file.type)) {
    return { ok: false, message: "Invalid file type. Please upload JPG, PNG, or WEBP." };
  }
  if (file.size > maxBytes) {
    return { ok: false, message: "File is too large. Maximum allowed size is 2MB." };
  }
  return { ok: true };
}

function setTestimonialAvatarError(message) {
  const error = document.getElementById("testimonialAvatarError");
  if (!error) return;
  error.textContent = message || "";
  error.style.display = message ? "block" : "none";
}

function renderTestimonialAvatarPreview(src) {
  const preview = document.getElementById("testimonialAvatarPreview");
  const image = document.getElementById("testimonialAvatarPreviewImage");
  if (!preview || !image) return;

  if (!src) {
    image.src = "";
    preview.style.display = "none";
    return;
  }

  image.src = src;
  preview.style.display = "flex";
}

function applyTestimonialAvatarFile(file) {
  const input = document.getElementById("testimonialAuthorAvatarFile");
  const check = validateTestimonialAvatar(file);

  if (!check.ok) {
    testimonialAvatarFile = null;
    if (input) input.value = "";
    renderTestimonialAvatarPreview(testimonialExistingAvatar);
    setTestimonialAvatarError(check.message);
    showToast(check.message);
    return;
  }

  testimonialAvatarFile = file;
  testimonialAvatarRemoved = false;
  setTestimonialAvatarError("");
  renderTestimonialAvatarPreview(URL.createObjectURL(file));
}

function removeTestimonialAvatar() {
  const input = document.getElementById("testimonialAuthorAvatarFile");
  testimonialAvatarFile = null;
  testimonialExistingAvatar = "";
  testimonialAvatarRemoved = true;
  if (input) input.value = "";
  setTestimonialAvatarError("");
  renderTestimonialAvatarPreview("");
}

function openTestimonialModal(id = null) {
  const modal = document.getElementById("testimonialModal");
  const title = document.getElementById("testimonialModalTitle");
  const idInput = document.getElementById("editTestimonialId");
  const avatarInput = document.getElementById("testimonialAuthorAvatarFile");
  const dropzone = document.getElementById("testimonialAvatarDropzone");
  const removeAvatarBtn = document.getElementById("removeTestimonialAvatarBtn");
  if (!modal || !title || !idInput) return;

  idInput.value = id || "";
  document.getElementById("testimonialAuthorName").value = "";
  document.getElementById("testimonialAuthorRole").value = "";
  document.getElementById("testimonialMessage").value = "";
  document.getElementById("testimonialOrder").value = "0";
  document.getElementById("testimonialIsActive").checked = true;
  testimonialAvatarFile = null;
  testimonialExistingAvatar = "";
  testimonialAvatarRemoved = false;
  if (avatarInput) avatarInput.value = "";
  setTestimonialAvatarError("");
  renderTestimonialAvatarPreview("");

  if (avatarInput && !avatarInput.dataset.bound) {
    avatarInput.addEventListener("change", function () {
      const file = this.files && this.files.length ? this.files[0] : null;
      if (file) applyTestimonialAvatarFile(file);
    });
    avatarInput.dataset.bound = "1";
  }

  if (dropzone && !dropzone.dataset.bound) {
    dropzone.addEventListener("click", () => {
      if (avatarInput) avatarInput.click();
    });

    dropzone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#0f1e3d";
      dropzone.style.background = "#eff6ff";
    });

    dropzone.addEventListener("dragleave", () => {
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";
    });

    dropzone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";

      const files = e.dataTransfer && e.dataTransfer.files ? Array.from(e.dataTransfer.files) : [];
      if (!files.length) return;

      if (avatarInput) {
        const transfer = new DataTransfer();
        transfer.items.add(files[0]);
        avatarInput.files = transfer.files;
      }
      applyTestimonialAvatarFile(files[0]);
    });

    dropzone.dataset.bound = "1";
  }

  if (removeAvatarBtn && !removeAvatarBtn.dataset.bound) {
    removeAvatarBtn.addEventListener("click", removeTestimonialAvatar);
    removeAvatarBtn.dataset.bound = "1";
  }

  if (id) {
    title.textContent = "Edit Testimonial";
    const item = API_TESTIMONIALS.find(t => t.id === id);
    if (item) {
      document.getElementById("testimonialAuthorName").value = item.author_name || "";
      document.getElementById("testimonialAuthorRole").value = item.author_role || "";
      testimonialExistingAvatar = item.author_avatar || "";
      renderTestimonialAvatarPreview(testimonialExistingAvatar);
      document.getElementById("testimonialMessage").value = item.message || "";
      document.getElementById("testimonialOrder").value = item.order != null ? item.order : 0;
      document.getElementById("testimonialIsActive").checked = !!item.is_active;
    }
  } else {
    title.textContent = "Add Testimonial";
  }

  modal.style.display = "flex";
}

function closeTestimonialModal() {
  const modal = document.getElementById("testimonialModal");
  if (modal) modal.style.display = "none";
}

async function saveTestimonial() {
  const id = document.getElementById("editTestimonialId").value;
  const data = {
    author_name: document.getElementById("testimonialAuthorName").value.trim(),
    author_role: document.getElementById("testimonialAuthorRole").value.trim(),
    message: document.getElementById("testimonialMessage").value.trim(),
    order: Number(document.getElementById("testimonialOrder").value || 0),
    is_active: document.getElementById("testimonialIsActive").checked
  };

  if (!data.author_name || !data.author_role || !data.message) {
    showToast("Author name, role, and message are required.");
    return;
  }

  const btn = document.getElementById("saveTestimonialBtn");
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i data-iconsax="loader" class="animate-spin" style="width:14px;height:14px;margin-right:8px;"></i> Saving...';

  try {
    const payload = new FormData();
    payload.append("author_name", data.author_name);
    payload.append("author_role", data.author_role);
    payload.append("message", data.message);
    payload.append("order", String(data.order));
    payload.append("is_active", data.is_active ? "1" : "0");

    if (testimonialAvatarFile) {
      payload.append("author_avatar_file", testimonialAvatarFile);
    } else if (id && testimonialAvatarRemoved) {
      payload.append("clear_avatar", "1");
    }

    if (id) {
      payload.append("_method", "PATCH");
    }

    const res = await apiFetch(id ? `/api/testimonials/${id}` : "/api/testimonials", {
      method: "POST",
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      },
      body: payload
    });

    if (res.ok) {
      showToast(id ? "Testimonial updated" : "Testimonial created");
      closeTestimonialModal();
      reloadContentSoon(loadTestimonials);
    } else {
      const err = await res.json();
      throw new Error(err.message || "Failed to save testimonial");
    }
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
}

async function deleteTestimonial(id) {
  showConfirmModal({
    title: "Delete Testimonial?",
    message: "Are you sure you want to delete this testimonial? It will no longer be visible on the homepage.",
    confirmText: "Delete",
    onConfirm: async () => {
      try {
        const res = await apiFetch(`/api/testimonials/${id}`, {
          method: "DELETE",
          headers: {
            "Authorization": "Bearer " + sessionStorage.getItem("_at"),
            "Accept": "application/json"
          }
        });

        if (res.ok) {
          showToast("Testimonial deleted");
          reloadContentSoon(loadTestimonials);
        } else {
          throw new Error("Failed to delete testimonial");
        }
      } catch (err) {
        showToast(err.message);
      }
    }
  });
}

async function loadFacultyStaff() {
  const tbody = document.getElementById("facultyStaffTableBody");
  if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\">Loading...</td></tr>";
  clearContentPagination("facultyStaff");

  try {
    const res = await apiFetch("/api/faculty-staff", {
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      }
    });
    const result = await res.json();
    API_FACULTY_STAFF = result.data || [];
    renderFacultyStaffTable();
  } catch (err) {
    console.error("Failed to load faculty/staff:", err);
    if (tbody) tbody.innerHTML = "<tr><td colspan=\"5\" style=\"color:var(--red)\">Failed to load.</td></tr>";
  }
}

function renderFacultyStaffTable() {
  const tbody = document.getElementById("facultyStaffTableBody");
  if (!tbody) return;

  if (!API_FACULTY_STAFF.length) {
    tbody.innerHTML = "<tr><td colspan=\"5\"><p class=\"empty-state\">No faculty/staff members yet.</p></td></tr>";
    clearContentPagination("facultyStaff");
    return;
  }

  const pageItems = getContentPageItems(API_FACULTY_STAFF, "facultyStaff");

  tbody.innerHTML = pageItems.map(item => {
    const id = escapeHtml(String(item.id || ""));
    return `
      <tr>
        <td>
          <div style="font-weight:600;">${escapeHtml(item.name || "")}</div>
          <div style="font-size:12px;color:var(--text-3);max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${escapeHtml(item.note || "")}">${escapeHtml(item.note || "")}</div>
        </td>
        <td>${escapeHtml(item.role || "")}</td>
        <td>${item.order != null ? item.order : 0}</td>
        <td>
          <span class="badge ${item.is_active ? "badge--approved" : "badge--rejected"}">
            ${item.is_active ? "Active" : "Inactive"}
          </span>
        </td>
        <td>
          <div style="display:flex; gap:8px;">
            <button class="btn-view-label" onclick="openFacultyStaffModal('${id}')">Edit</button>
            <button class="btn-view-label" style="background:#fee2e2; color:#b91c1c;" onclick="deleteFacultyStaff('${id}')">Delete</button>
          </div>
        </td>
      </tr>
    `;
  }).join("");
  renderContentPagination("facultyStaff", API_FACULTY_STAFF.length);
}

function validateFacultyStaffImage(file) {
  if (!file) return { ok: true };
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
  const maxBytes = 2 * 1024 * 1024;

  if (!allowedTypes.includes(file.type)) {
    return { ok: false, message: "Invalid file type. Please upload JPG, PNG, or WEBP." };
  }
  if (file.size > maxBytes) {
    return { ok: false, message: "File is too large. Maximum allowed size is 2MB." };
  }
  return { ok: true };
}

function setFacultyStaffImageError(message) {
  const error = document.getElementById("facultyStaffImageError");
  if (!error) return;
  error.textContent = message || "";
  error.style.display = message ? "block" : "none";
}

function renderFacultyStaffImagePreview(src) {
  const preview = document.getElementById("facultyStaffImagePreview");
  const img = document.getElementById("facultyStaffImagePreviewImg");
  if (!preview || !img) return;

  if (!src) {
    img.src = "";
    preview.style.display = "none";
    return;
  }

  img.src = src;
  preview.style.display = "flex";
}

function applyFacultyStaffImageFile(file) {
  const input = document.getElementById("facultyStaffImageFile");
  const check = validateFacultyStaffImage(file);

  if (!check.ok) {
    facultyStaffImageFile = null;
    if (input) input.value = "";
    renderFacultyStaffImagePreview(facultyStaffExistingImage);
    setFacultyStaffImageError(check.message);
    showToast(check.message);
    return;
  }

  facultyStaffImageFile = file;
  facultyStaffImageRemoved = false;
  setFacultyStaffImageError("");
  renderFacultyStaffImagePreview(URL.createObjectURL(file));
}

function removeFacultyStaffImage() {
  const input = document.getElementById("facultyStaffImageFile");
  facultyStaffImageFile = null;
  facultyStaffExistingImage = "";
  facultyStaffImageRemoved = true;
  if (input) input.value = "";
  setFacultyStaffImageError("");
  renderFacultyStaffImagePreview("");
}

function openFacultyStaffModal(id = null) {
  const modal = document.getElementById("facultyStaffModal");
  const title = document.getElementById("facultyStaffModalTitle");
  const idInput = document.getElementById("editFacultyStaffId");
  const imageInput = document.getElementById("facultyStaffImageFile");
  const dropzone = document.getElementById("facultyStaffImageDropzone");
  const removeImageBtn = document.getElementById("removeFacultyStaffImageBtn");
  if (!modal || !title || !idInput) return;

  idInput.value = id || "";
  document.getElementById("facultyStaffName").value = "";
  document.getElementById("facultyStaffRole").value = "";
  document.getElementById("facultyStaffNote").value = "";
  document.getElementById("facultyStaffIcon").value = "user-round";
  document.getElementById("facultyStaffOrder").value = "0";
  document.getElementById("facultyStaffIsActive").checked = true;
  facultyStaffImageFile = null;
  facultyStaffExistingImage = "";
  facultyStaffImageRemoved = false;
  if (imageInput) imageInput.value = "";
  setFacultyStaffImageError("");
  renderFacultyStaffImagePreview("");

  if (imageInput && !imageInput.dataset.bound) {
    imageInput.addEventListener("change", function () {
      const file = this.files && this.files.length ? this.files[0] : null;
      if (file) applyFacultyStaffImageFile(file);
    });
    imageInput.dataset.bound = "1";
  }

  if (dropzone && !dropzone.dataset.bound) {
    dropzone.addEventListener("click", () => {
      if (imageInput) imageInput.click();
    });

    dropzone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#0f1e3d";
      dropzone.style.background = "#eff6ff";
    });

    dropzone.addEventListener("dragleave", () => {
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";
    });

    dropzone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropzone.style.borderColor = "#cbd5e1";
      dropzone.style.background = "#f8fafc";

      const files = e.dataTransfer && e.dataTransfer.files ? Array.from(e.dataTransfer.files) : [];
      if (!files.length) return;

      if (imageInput) {
        const transfer = new DataTransfer();
        transfer.items.add(files[0]);
        imageInput.files = transfer.files;
      }
      applyFacultyStaffImageFile(files[0]);
    });

    dropzone.dataset.bound = "1";
  }

  if (removeImageBtn && !removeImageBtn.dataset.bound) {
    removeImageBtn.addEventListener("click", removeFacultyStaffImage);
    removeImageBtn.dataset.bound = "1";
  }

  if (id) {
    title.textContent = "Edit Faculty/Staff";
    const item = API_FACULTY_STAFF.find(member => String(member.id) === String(id));
    if (item) {
      document.getElementById("facultyStaffName").value = item.name || "";
      document.getElementById("facultyStaffRole").value = item.role || "";
      document.getElementById("facultyStaffNote").value = item.note || "";
      document.getElementById("facultyStaffIcon").value = item.icon || "user-round";
      facultyStaffExistingImage = item.image || "";
      renderFacultyStaffImagePreview(facultyStaffExistingImage);
      document.getElementById("facultyStaffOrder").value = item.order != null ? item.order : 0;
      document.getElementById("facultyStaffIsActive").checked = !!item.is_active;
    }
  } else {
    title.textContent = "Add Faculty/Staff";
  }

  modal.style.display = "flex";
}

function closeFacultyStaffModal() {
  const modal = document.getElementById("facultyStaffModal");
  if (modal) modal.style.display = "none";
}

async function saveFacultyStaff() {
  const id = document.getElementById("editFacultyStaffId").value;
  const data = {
    name: document.getElementById("facultyStaffName").value.trim(),
    role: document.getElementById("facultyStaffRole").value.trim(),
    note: document.getElementById("facultyStaffNote").value.trim(),
    icon: document.getElementById("facultyStaffIcon").value.trim() || "user-round",
    order: Number(document.getElementById("facultyStaffOrder").value || 0),
    is_active: document.getElementById("facultyStaffIsActive").checked
  };

  if (!data.name || !data.role || !data.note) {
    showToast("Name, role, and note are required.");
    return;
  }

  const btn = document.getElementById("saveFacultyStaffBtn");
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i data-iconsax="loader" class="animate-spin" style="width:14px;height:14px;margin-right:8px;"></i> Saving...';

  try {
    const payload = new FormData();
    payload.append("name", data.name);
    payload.append("role", data.role);
    payload.append("note", data.note);
    payload.append("icon", data.icon);
    payload.append("order", String(data.order));
    payload.append("is_active", data.is_active ? "1" : "0");

    if (facultyStaffImageFile) {
      payload.append("image_file", facultyStaffImageFile);
    } else if (id && facultyStaffImageRemoved) {
      payload.append("clear_image", "1");
    }

    if (id) {
      payload.append("_method", "PATCH");
    }

    const res = await apiFetch(id ? `/api/faculty-staff/${encodeURIComponent(id)}` : "/api/faculty-staff", {
      method: "POST",
      headers: {
        "Authorization": "Bearer " + sessionStorage.getItem("_at"),
        "Accept": "application/json"
      },
      body: payload
    });

    if (res.ok) {
      showToast(id ? "Faculty/staff member updated" : "Faculty/staff member added");
      closeFacultyStaffModal();
      reloadContentSoon(loadFacultyStaff);
    } else {
      const err = await res.json();
      throw new Error(err.message || "Failed to save faculty/staff member");
    }
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
  }
}

async function deleteFacultyStaff(id) {
  showConfirmModal({
    title: "Delete Faculty Member?",
    message: "Are you sure you want to remove this faculty member? This will update the About page immediately.",
    confirmText: "Delete",
    onConfirm: async () => {
      try {
        const res = await apiFetch(`/api/faculty-staff/${encodeURIComponent(id)}`, {
          method: "DELETE",
          headers: {
            "Authorization": "Bearer " + sessionStorage.getItem("_at"),
            "Accept": "application/json"
          }
        });

        if (res.ok) {
          showToast("Faculty member deleted");
          reloadContentSoon(loadFacultyStaff);
        } else {
          throw new Error("Failed to delete faculty member");
        }
      } catch (err) {
        showToast(err.message);
      }
    }
  });
}

async function toggleProgram(id, name, btn) {
  const program = API_PROGRAMS.find(p => Number(p.id) === Number(id));
  const current = (programEnabled[name] !== undefined) ? !!programEnabled[name] : !!(program && program.is_active);
  const next = !current;
  const slotsLeft = Number(program && program.slots_left != null ? program.slots_left : 0);

  if (next && Number.isFinite(slotsLeft) && slotsLeft <= 0) {
    showToast('Set Slots Left above 0 before reopening this program.');
    return;
  }

  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
    showToast('Please log in to update program status.');
    return;
  }

  programEnabled[name] = next;
  btn.disabled = true;
  btn.className = `prog-toggle ${next ? 'on' : 'off'}`;
  btn.innerHTML = `<i data-iconsax="${next ? 'check-circle' : 'x'}" style="width:13px;height:13px"></i> ${next ? 'Active' : 'Disabled'}`;
  if (typeof iconsax !== 'undefined') iconsax.createIcons();

  try {
    const updated = await AdmissionAPI.updateProgramStatus(id, next);
    if (program) program.is_active = updated.is_active !== false;
    programEnabled[name] = updated.is_active !== false;
    
    const isFull = Number(program && program.slots_left != null ? program.slots_left : 0) <= 0;
    const statusText = programEnabled[name] ? (isFull ? 'Full' : 'Active') : 'Disabled';
    const statusClass = programEnabled[name] ? (isFull ? 'full' : 'on') : 'off';
    const statusIcon = programEnabled[name] ? (isFull ? 'info-circle' : 'check-circle') : 'x';

    btn.className = `prog-toggle ${statusClass}`;
    btn.innerHTML = `<i data-iconsax="${statusIcon}" style="width:13px;height:13px"></i> ${statusText}`;
    showToast(`"${shortProg(name)}" is now ${statusText}`);
  } catch (err) {
    programEnabled[name] = current;
    btn.className = `prog-toggle ${current ? 'on' : 'off'}`;
    btn.innerHTML = `<i data-iconsax="${current ? 'check-circle' : 'x'}" style="width:13px;height:13px"></i> ${current ? 'Active' : 'Disabled'}`;
    showToast(err && err.message ? err.message : 'Failed to update program status.');
  } finally {
    btn.disabled = false;
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }
}

/* ─── INTERVIEW SCHEDULE ─── */
function renderInterviewsTable() {
  const programs = getPrograms();
  const tbody = document.getElementById('interviewTableBody');
  if (!tbody) return;

  const filtered = programs.filter(p => {
    const filter = interviewFilter.toLowerCase();
    if (filter === 'all') return true;

    const cat = (p.category || '').toLowerCase();
    // Handle special case for Arts & Sciences
    if (filter === 'arts & sciences' && cat === 'arts&sciences') return true;

    return cat === filter;
  });

  if (filtered.length === 0) {
    tbody.innerHTML = '<tr><td colspan="4" class="empty-state">No courses found for this category.</td></tr>';
    return;
  }

  tbody.innerHTML = filtered.map(p => {
    const schedule = p.interview_schedule || 'Mon-Fri, 9AM-4PM';
    const status = p.interview_status || 'Ongoing';
    const sClass = status === 'Ongoing' ? 'badge--approved' : (status === 'Paused' ? 'badge--pending' : 'badge--rejected');

    return `<tr>
      <td>
        <div style="font-weight:600;color:var(--navy)">${p.name}</div>
        <div style="font-size:12px;color:var(--text-3)">${p.code}</div>
      </td>
      <td>
        <span class="badge" style="background:var(--navy-pale);color:var(--navy-mid)">${p.department || '—'}</span>
      </td>
      <td>
        <div style="display:flex;align-items:center;gap:8px">
          <i data-iconsax="clock" style="width:14px;height:14px;color:var(--text-3)"></i>
          <span>${schedule}</span>
          <button class="btn-ghost" style="padding:2px;margin-left:4px;color:var(--navy)" onclick="openEditCourseModal(${p.id}, '${(p.name || '').replace(/'/g, "\\'")}', '${(p.department || '').replace(/'/g, "\\'")}', '${(p.code || '').replace(/'/g, "\\'")}', '${schedule}', '${status}')">
            <i data-iconsax="edit" style="width:12px;height:12px"></i>
          </button>
        </div>
      </td>
      <td><span class="badge ${sClass}">${status}</span></td>
      <td>
        <button class="btn-primary" style="padding:6px 12px;font-size:12px" onclick="openStudentScheduling('${(p.name || '').replace(/'/g, "\\'")}')">
          <i data-iconsax="users" style="width:14px;height:14px;margin-right:4px"></i>
          Schedule Students
        </button>
      </td>
    </tr>`;
  }).join('');
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
}

let currentSchedulingCourse = '';
let currentSchedulingData = []; // To keep track of rows for searching

function normalizeProgramKey(value) {
  return String(value || '')
    .trim()
    .toLowerCase()
    .replace(/\s+/g, ' ')
    .replace(/[^\w\s]/g, '');
}

async function openStudentScheduling(courseName) {
  currentSchedulingCourse = courseName;
  const header = document.getElementById('schedulingCourseName');
  if (header) header.textContent = 'Schedule Students — ' + courseName;

  // Switch page immediately to improve perceived performance
  showPage('student-scheduling');
  renderSchedulingSkeleton();

  // Find the program ID for this course name
  const prog = getPrograms().find(p => p.name === courseName);
  const progId = prog ? prog.id : null;

  if (!progId) {
    showToast('Error: Program not found.', 'error');
    return;
  }

  // Fetch existing interviews from database
  try {
    console.log(`Fetching interviews for Program ID: ${progId} (${courseName})`);
    const res = await AdmissionAPI.request(`/interviews?program_id=${progId}`);
    const existingInterviews = res.data || [];

    const combined = [...existingInterviews];

    currentSchedulingData = combined;
    renderSchedulingRows(combined);

    // Clear search input
    const searchInput = document.getElementById('schedulingSearchInput');
    if (searchInput) searchInput.value = '';

  } catch (err) {
    console.error('Scheduling Load Error:', err);
    if (err.data) console.error('Error Data:', err.data);
    currentSchedulingData = [];
    renderSchedulingRows([]);
    showPage('student-scheduling');

    const searchInput = document.getElementById('schedulingSearchInput');
    if (searchInput) searchInput.value = '';

    showToast('Could not load saved schedules. You can still add schedules manually.', 'warning');
  }
}


function renderSchedulingRows(data) {
  const tbody = document.getElementById('studentSchedulingTableBody');
  if (!tbody) return;

  tbody.innerHTML = '';

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="empty-state" id="noStudentsMsg">No students found. Click "Add Student" to start scheduling.</td></tr>`;
  } else {
    data.forEach(item => addSchedulingRow(item));
  }
}

function addSchedulingRow(data = {}) {
  const tbody = document.getElementById('studentSchedulingTableBody');
  if (!tbody) return;

  const msg = document.getElementById('noStudentsMsg');
  if (msg) msg.closest('tr').remove();

  const tr = document.createElement('tr');
  tr.className = 'scheduling-row';
  const name = data.student_name || '';
  const ref = data.reference_number || '';
  const status = normalizeInterviewStatus(data.status);
  const appId = data.application_id || '';

  tr.innerHTML = `
    <td>
      <input type="hidden" name="app_id" value="${appId}">
      <input type="text" name="student_name" class="fi" style="padding:6px;font-size:13px;font-weight:600" value="${name}" placeholder="Enter student name...">
    </td>
    <td style="font-family:monospace;color:var(--text-3)">
      <input type="text" name="ref_num" class="fi" style="padding:6px;font-size:13px;width:100%" value="${ref}" placeholder="e.g. BTECH-2026-123456">
    </td>
    <td><input type="date" name="int_date" class="fi" style="padding:6px;font-size:13px" value="${data.interview_date || ''}"></td>
    <td><input type="time" name="int_time" class="fi" style="padding:6px;font-size:13px" value="${data.interview_time || ''}"></td>
    <td>
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
        <select name="int_status" class="fi" style="padding:6px;font-size:13px;min-width:132px" onchange="onSchedulingStatusChange(this)">
          <option value="Pending" ${status === 'Pending' ? 'selected' : ''}>Pending</option>
          <option value="Scheduled" ${status === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
          <option value="Completed" ${status === 'Completed' ? 'selected' : ''}>Completed</option>
          <option value="No Show" ${status === 'No Show' ? 'selected' : ''}>No Show</option>
          <option value="Cancelled" ${status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
        </select>
        <span class="badge ${statusClass(status)} scheduling-status-badge">${status}</span>
      </div>
    </td>
    <td style="text-align:center;">
      <button class="btn-ghost" onclick="removeSchedulingRow(this)" style="color:var(--red);padding:6px;" title="Remove Applicant">
        <i data-iconsax="trash-2" style="width:16px;height:16px;"></i>
      </button>
    </td>
  `;
  tbody.appendChild(tr);
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
}

function onSchedulingStatusChange(sel) {
  const tr = sel.closest('tr');
  if (!tr) return;
  const badge = tr.querySelector('.scheduling-status-badge');
  const val = normalizeInterviewStatus(sel.value || 'Pending');
  sel.value = val;
  if (badge) {
    badge.className = 'badge ' + statusClass(val) + ' scheduling-status-badge';
    badge.textContent = val;
  }
}

function removeSchedulingRow(btn) {
  const tr = btn.closest('tr');
  const studentName = tr.querySelector('[name="student_name"]')?.value || "this student";

  showConfirmModal({
    title: "Remove from Schedule?",
    message: `Are you sure you want to remove <strong>${studentName}</strong> from this session?`,
    confirmText: "Remove",
    onConfirm: () => {
      const appId = tr.querySelector('[name="app_id"]')?.value;
      const refNum = tr.querySelector('[name="ref_num"]')?.value;

      if (appId) {
        currentSchedulingData = currentSchedulingData.filter(ci => String(ci.application_id) !== String(appId));
      } else if (refNum) {
        currentSchedulingData = currentSchedulingData.filter(ci => ci.reference_number !== refNum);
      }

      tr.remove();

      // If table is now empty, show empty state message
      const tbody = document.getElementById('studentSchedulingTableBody');
      if (tbody && tbody.querySelectorAll('tr').length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="empty-state" id="noStudentsMsg">No students found. Click "Add Student" or "Select Applicants" to start scheduling.</td></tr>`;
      }
    }
  });
}

function showAddStudentRow() {
  addSchedulingRow();
}

function applySchedulingSearch() {
  const term = document.getElementById('schedulingSearchInput').value.toLowerCase();
  const rows = document.querySelectorAll('.scheduling-row');

  rows.forEach(row => {
    const name = row.querySelector('[name="student_name"]').value.toLowerCase();
    const ref = row.querySelector('[name="ref_num"]').value.toLowerCase();
    if (name.includes(term) || ref.includes(term)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

async function saveAllStudentSchedules() {
  const prog = getPrograms().find(p => p.name === currentSchedulingCourse);
  if (!prog) return;

  const rows = document.querySelectorAll('.scheduling-row');
  const schedules = [];

  rows.forEach(row => {
    const name = row.querySelector('[name="student_name"]').value;
    const date = row.querySelector('[name="int_date"]').value;
    const time = row.querySelector('[name="int_time"]').value;
    const status = normalizeInterviewStatus(row.querySelector('[name="int_status"]')?.value || 'Pending');

    if (name && date && time) {
      schedules.push({
        application_id: row.querySelector('[name="app_id"]').value || null,
        student_name: name,
        reference_number: row.querySelector('[name="ref_num"]').value === 'MANUAL' ? null : row.querySelector('[name="ref_num"]').value,
        interview_date: date,
        interview_time: time,
        status: status
      });
    }
  });

  if (schedules.length === 0) {
    showToast('No valid schedules to save. Please fill in Name, Date, and Time.', 'warning');
    return;
  }

  try {
    await AdmissionAPI.request(`/interviews/sync/${prog.id}`, {
      method: 'POST',
      body: JSON.stringify({ schedules })
    });
    showToast('Schedules saved successfully and notification emails sent!');
    showPage('interviews');
    refreshDataSoon();
  } catch (err) {
    console.error(err);
    showToast('Failed to save schedules', 'error');
  }
}



// Remove old modal init if any

let applicantModalData = [];

function openSelectApplicantModal() {
  const modal = document.getElementById('selectApplicantModal');
  if (!modal) return;

  document.getElementById('applicantSearchInput').value = '';

  const selectedProgram = getPrograms().find(p => p.name === currentSchedulingCourse);
  const selectedId = selectedProgram && selectedProgram.id != null ? String(selectedProgram.id) : '';
  const selectedNames = [
    currentSchedulingCourse,
    selectedProgram?.name,
    selectedProgram?.code,
  ].map(normalizeProgramKey).filter(Boolean);

  // Get applicants for the current course
  const applicants = getApplications().filter(a => {
    const raw = a.raw || {};
    const appProgramId = a.programId != null ? String(a.programId) : (raw.program_id != null ? String(raw.program_id) : '');
    if (selectedId && appProgramId && selectedId === appProgramId) return true;

    const choices = [
      a.firstChoice,
      raw.first_choice,
      raw.program_name,
      raw.program?.name,
      raw.program?.code,
    ].map(normalizeProgramKey).filter(Boolean);

    return choices.some(choice => selectedNames.includes(choice));
  });

  // Filter out applicants already in currentSchedulingData
  applicantModalData = applicants.filter(a => {
    return !currentSchedulingData.find(ci =>
      (ci.application_id && a.id && String(ci.application_id) === String(a.id)) ||
      (ci.reference_number && a.ref && String(ci.reference_number) === String(a.ref))
    );
  });

  renderApplicantModalList(applicantModalData);
  modal.style.display = 'flex';
}

function closeSelectApplicantModal() {
  const modal = document.getElementById('selectApplicantModal');
  if (modal) modal.style.display = 'none';
}

function renderApplicantModalList(data) {
  const tbody = document.getElementById('applicantModalList');
  if (!tbody) return;

  if (data.length === 0) {
    tbody.innerHTML = '<tr><td colspan="3" class="empty-state">No unscheduled applicants found for this course.</td></tr>';
    return;
  }

  tbody.innerHTML = data.map(a => `
    <tr>
      <td>
        <div style="font-weight:600;color:var(--navy)">${a.applicant_name}</div>
        <div style="font-size:12px;color:var(--text-3);font-family:monospace">${a.ref}</div>
      </td>
      <td>
        <div style="font-weight:600">${avgGWA(a)}</div>
      </td>
      <td>
        <button class="btn-outline" style="padding:4px 8px;font-size:12px" onclick="addApplicantToSchedule(${a.id})">
          Add
        </button>
      </td>
    </tr>
  `).join('');
}

function filterApplicantModal() {
  const term = document.getElementById('applicantSearchInput').value.toLowerCase();
  const filtered = applicantModalData.filter(a =>
    a.applicant_name.toLowerCase().includes(term) ||
    a.ref.toLowerCase().includes(term)
  );
  renderApplicantModalList(filtered);
}

function addApplicantToSchedule(appId) {
  const applicant = applicantModalData.find(a => a.id === appId);
  if (!applicant) return;

  const newItem = {
    application_id: applicant.id,
    student_name: applicant.applicant_name,
    reference_number: applicant.ref,
    interview_date: '',
    interview_time: '',
    status: 'Pending'
  };

  currentSchedulingData.push(newItem);
  addSchedulingRow(newItem);

  // Remove from modal data and re-render
  applicantModalData = applicantModalData.filter(a => a.id !== appId);
  filterApplicantModal();

  showToast('Applicant added to schedule list');
}/* ─── REPORTS ─── */

// Cached server-side analytics from /api/admin/dashboard
let DASHBOARD_STATS = null;
let reportsRenderKey = '';
let reportsStatsPromise = null;

function buildReportsRenderKey() {
  const apps = getApplications();
  const programs = getPrograms();
  const statsKey = DASHBOARD_STATS
    ? JSON.stringify({
      total: DASHBOARD_STATS.total_applications,
      approved: DASHBOARD_STATS.approved_applications,
      avg: DASHBOARD_STATS.avg_gwa,
      pwd: DASHBOARD_STATS.pwd_count,
      solo: DASHBOARD_STATS.solo_parent_count,
      indigenous: DASHBOARD_STATS.indigenous_count,
      fours: DASHBOARD_STATS.four_ps_count,
      none: DASHBOARD_STATS.none_count,
      programs: Array.isArray(DASHBOARD_STATS.by_program) ? DASHBOARD_STATS.by_program.length : 0
    })
    : 'no-stats';
  const firstApp = apps.length ? apps[0] : null;
  const lastApp = apps.length ? apps[apps.length - 1] : null;
  return [
    statsKey,
    apps.length,
    programs.length,
    firstApp ? `${firstApp.id || ''}:${firstApp.status || ''}:${firstApp.filed || ''}` : '',
    lastApp ? `${lastApp.id || ''}:${lastApp.status || ''}:${lastApp.filed || ''}` : ''
  ].join('|');
}

async function initReports() {
  const grid = document.getElementById('reportsGrid');
  if (!grid) return;

  const currentKey = buildReportsRenderKey();
  if (reportsRenderKey === currentKey && grid.children.length && gwaChart && eligChart) {
    return;
  }
  // Show loading state
  if (!grid.children.length) grid.innerHTML = `
    <div class="report-stat-card" style="opacity:0.5;grid-column:1/-1;text-align:center;padding:24px;">
      <div class="report-stat-label">Loading analytics…</div>
    </div>`;

  try {
    // Try to get server-side analytics (more accurate, includes computed avg_gwa from DB)
    if (!DASHBOARD_STATS && typeof AdmissionAPI !== 'undefined' && AdmissionAPI.getToken()) {
      reportsStatsPromise = reportsStatsPromise || AdmissionAPI.getDashboardStats();
      DASHBOARD_STATS = await reportsStatsPromise;
    }
  } catch (e) {
    console.warn('Could not load dashboard stats from API, falling back to client-side:', e);
    DASHBOARD_STATS = null;
  } finally {
    reportsStatsPromise = null;
  }

  const list = getApplications();
  const total = Number(DASHBOARD_STATS?.total_applications ?? list.length ?? 0);
  const approved = Number(DASHBOARD_STATS?.approved_applications ?? list.filter(a => a.status === 'Approved').length ?? 0);
  const yes = v => String(v || '').trim().toLowerCase() === 'yes';
  const pwdCount = Number(DASHBOARD_STATS?.pwd_count ?? list.filter(a => yes(a.pwd)).length ?? 0);
  const indigenousCount = Number(DASHBOARD_STATS?.indigenous_count ?? list.filter(a => yes(a.indigenous)).length ?? 0);
  const foursCount = Number(DASHBOARD_STATS?.four_ps_count ?? list.filter(a => yes(a.fours)).length ?? 0);
  const avgGWAAll = DASHBOARD_STATS && DASHBOARD_STATS.avg_gwa != null
    ? parseFloat(DASHBOARD_STATS.avg_gwa).toFixed(2)
    : (total ? (list.reduce((s, a) => s + ((parseFloat(a.g11) || 0) + (parseFloat(a.g12) || 0)) / 2, 0) / total).toFixed(2) : '0.00');
  const progCount = Number(Array.isArray(DASHBOARD_STATS?.by_program) ? DASHBOARD_STATS.by_program.length : getPrograms().length);

  grid.innerHTML = [
    { label: 'Total Applications', value: total, sub: 'S.Y. 2025–2026' },
    { label: 'Overall Approval Rate', value: total ? Math.round(approved / total * 100) + '%' : '0%', sub: `${approved} approved` },
    { label: 'Overall Average GWA', value: avgGWAAll, sub: 'Across all applicants' },
    { label: 'PWD Applicants', value: pwdCount, sub: 'With disability' },
    { label: 'Indigenous Applicants', value: indigenousCount, sub: 'IP / tribe members' },
    { label: '4Ps Beneficiaries', value: foursCount, sub: 'Pantawid recipients' },
    { label: 'Programs Offered', value: progCount, sub: 'Active programs' },
  ].map(s => `
    <div class="report-stat-card">
      <div class="report-stat-label">${s.label}</div>
      <div class="report-stat-value">${s.value}</div>
      <div class="report-stat-sub">${s.sub}</div>
    </div>
  `).join('');

  renderReportTable();
  initReportCharts();
  reportsRenderKey = buildReportsRenderKey();
}

function renderReportTable() {
  // Use server-side per-program breakdown if available
  if (DASHBOARD_STATS && Array.isArray(DASHBOARD_STATS.by_program) && DASHBOARD_STATS.by_program.length > 0) {
    const programs = getPrograms();
    document.getElementById('rptTableBody').innerHTML = DASHBOARD_STATS.by_program.map(row => {
      const prog = programs.find(p => p.name === row.first_choice);
      const dept = prog ? (prog.department || '—') : '—';
      const gwa = row.avg_gwa != null ? parseFloat(row.avg_gwa).toFixed(1) : '—';
      return `<tr>
        <td style="font-weight:600">${escapeHtml(row.first_choice || '—')}</td>
        <td>${escapeHtml(dept)}</td>
        <td style="font-weight:700">${row.total || 0}</td>
        <td style="color:var(--green);font-weight:600">${row.approved || 0}</td>
        <td style="color:var(--yellow);font-weight:600">${row.pending || 0}</td>
        <td style="color:var(--red);font-weight:600">${row.rejected || 0}</td>
        <td style="font-weight:700;color:var(--navy-mid)">${gwa}</td>
      </tr>`;
    }).join('');
    return;
  }

  // Fallback: build from client-side application data
  const progCounts = {}, progStatuses = {}, progGWAs = {};
  getApplications().forEach(a => {
    const p = a.firstChoice;
    if (!p) return;
    progCounts[p] = (progCounts[p] || 0) + 1;
    progStatuses[p] = progStatuses[p] || { Approved: 0, Pending: 0, Rejected: 0, 'Interview Scheduled': 0 };
    progStatuses[p][a.status] = (progStatuses[p][a.status] || 0) + 1;
    progGWAs[p] = progGWAs[p] || [];
    const g11 = parseFloat(a.g11); const g12 = parseFloat(a.g12);
    if (!isNaN(g11) && !isNaN(g12)) progGWAs[p].push((g11 + g12) / 2);
    else if (!isNaN(g11)) progGWAs[p].push(g11);
    else if (!isNaN(g12)) progGWAs[p].push(g12);
  });
  document.getElementById('rptTableBody').innerHTML = Object.entries(progCounts).sort((a, b) => b[1] - a[1]).map(([name, count]) => {
    const st = progStatuses[name] || {};
    const gwaArr = progGWAs[name] || [];
    const gwa = gwaArr.length ? (gwaArr.reduce((s, v) => s + v, 0) / gwaArr.length).toFixed(1) : '—';
    const prog = getPrograms().find(p => p.name === name);
    return `<tr>
      <td style="font-weight:600">${escapeHtml(name)}</td>
      <td>${prog ? escapeHtml(prog.department || '—') : '—'}</td>
      <td style="font-weight:700">${count}</td>
      <td style="color:var(--green);font-weight:600">${st.Approved || 0}</td>
      <td style="color:var(--yellow);font-weight:600">${(st.Pending || 0) + (st['Interview Scheduled'] || 0)}</td>
      <td style="color:var(--red);font-weight:600">${st.Rejected || 0}</td>
      <td style="font-weight:700;color:var(--navy-mid)">${gwa}</td>
    </tr>`;
  }).join('');
}

function initReportCharts() {
  // GWA distribution — prefer server-side by_program data
  let gwaLabels = [], gwaData = [];
  if (DASHBOARD_STATS && Array.isArray(DASHBOARD_STATS.by_program)) {
    const sorted = [...DASHBOARD_STATS.by_program]
      .filter(r => r.avg_gwa != null)
      .sort((a, b) => b.avg_gwa - a.avg_gwa)
      .slice(0, 8);
    gwaLabels = sorted.map(r => shortProg(r.first_choice));
    gwaData = sorted.map(r => parseFloat(r.avg_gwa).toFixed(1));
  } else {
    const progGWAs = {};
    getApplications().forEach(a => {
      if (!a.firstChoice) return;
      progGWAs[a.firstChoice] = progGWAs[a.firstChoice] || [];
      const g11 = parseFloat(a.g11); const g12 = parseFloat(a.g12);
      if (!isNaN(g11) && !isNaN(g12)) progGWAs[a.firstChoice].push((g11 + g12) / 2);
      else if (!isNaN(g11)) progGWAs[a.firstChoice].push(g11);
      else if (!isNaN(g12)) progGWAs[a.firstChoice].push(g12);
    });
    const sorted = Object.entries(progGWAs)
      .map(([k, v]) => ({ name: shortProg(k), avg: v.reduce((s, x) => s + x, 0) / v.length }))
      .sort((a, b) => b.avg - a.avg).slice(0, 8);
    gwaLabels = sorted.map(s => s.name);
    gwaData = sorted.map(s => +s.avg.toFixed(1));
  }

  const gwaCanvas = document.getElementById('gwaChart');
  const nextGwaLabels = gwaLabels.length ? gwaLabels : ['No data'];
  const nextGwaData = gwaData.length ? gwaData : [0];
  if (gwaChart) {
    gwaChart.data.labels = nextGwaLabels;
    gwaChart.data.datasets[0].data = nextGwaData;
    gwaChart.update('none');
  } else if (gwaCanvas) {
    gwaChart = new Chart(gwaCanvas, {
      type: 'bar',
      data: { labels: nextGwaLabels, datasets: [{ label: 'Avg GWA', data: nextGwaData, backgroundColor: '#254d82', borderRadius: 6, borderSkipped: false }] },
      options: { responsive: true, maintainAspectRatio: false, animation: false, plugins: { legend: { display: false } }, scales: { y: { min: 70, max: 100, grid: { color: 'rgba(0,0,0,.05)' } }, x: { grid: { display: false } } } }
    });
  }

  // Eligibility doughnut — prefer server-side counts
  let pwd = 0, solo = 0, indigenous = 0, fours = 0, none = 0;
  if (DASHBOARD_STATS) {
    pwd = DASHBOARD_STATS.pwd_count || 0;
    solo = DASHBOARD_STATS.solo_parent_count || 0;
    indigenous = DASHBOARD_STATS.indigenous_count || 0;
    fours = DASHBOARD_STATS.four_ps_count || 0;
    none = DASHBOARD_STATS.none_count || 0;
  } else {
    const list = getApplications();
    const yes = v => String(v || '').trim().toLowerCase() === 'yes';
    pwd = list.filter(a => yes(a.pwd)).length;
    solo = list.filter(a => yes(a.solo)).length;
    indigenous = list.filter(a => yes(a.indigenous)).length;
    fours = list.filter(a => yes(a.fours)).length;
    none = list.filter(a => !yes(a.pwd) && !yes(a.solo) && !yes(a.indigenous) && !yes(a.fours)).length;
  }
  const eligCanvas = document.getElementById('eligChart');
  const nextEligData = [none, solo, indigenous, fours, pwd];
  if (eligChart) {
    eligChart.data.datasets[0].data = nextEligData;
    eligChart.update('none');
  } else if (eligCanvas) {
    eligChart = new Chart(eligCanvas, {
      type: 'doughnut',
      data: { labels: ['No Special Classification', 'Solo Parent', 'Indigenous', '4Ps', 'PWD'], datasets: [{ data: nextEligData, backgroundColor: ['#1b3557', '#c9933a', '#7c3aed', '#16a34a', '#2563eb'], borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }] },
      options: { responsive: true, maintainAspectRatio: false, animation: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } } }, cutout: '60%' }
    });
  }
}

/* ─── SETTINGS ─── */
let settingsLoaded = false;

async function initSettings() {
  // Set total from client-side data immediately
  const totalEl = document.getElementById('settingTotal');
  if (totalEl) totalEl.value = getApplications().length;

  // Load settings from DB via API
  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) return;

  try {
    const s = await AdmissionAPI.getSettings();
    settingsLoaded = true;
    applySystemSettingsUI(s);

    // Academic Year & Deadline card
    const syEl = document.getElementById('settingSY');
    if (syEl && s.school_year) {
      // Try to match existing option; if not found, add it
      let found = false;
      for (let i = 0; i < syEl.options.length; i++) {
        if (syEl.options[i].value === s.school_year || syEl.options[i].text === s.school_year) {
          syEl.selectedIndex = i;
          found = true;
          break;
        }
      }
      if (!found) {
        const opt = document.createElement('option');
        opt.value = s.school_year;
        opt.textContent = s.school_year;
        syEl.appendChild(opt);
        syEl.value = s.school_year;
      }
    }

    // Update topbar school year display
    if (s.school_year) {
      const topbarSY = document.getElementById('topbarSY');
      if (topbarSY) topbarSY.textContent = s.school_year;
    }

    setDeadlineFieldValue(s.application_deadline);

    // Interview schedule text (the text input in the first card)
    const interviewInputs = document.querySelectorAll('#page-settings .settings-input[type="text"]:not([readonly])');
    // First card text inputs: interview_schedule_text, institution_name, campus_address
    const textInputMap = [
      { id: 'settingInterviewSchedule', key: 'interview_schedule_text' },
      { id: 'settingInstitutionName', key: 'institution_name' },
      { id: 'settingAdmissionsEmail', key: 'admissions_email' },
      { id: 'settingCampusAddress', key: 'campus_address' },
    ];
    textInputMap.forEach(({ id, key }) => {
      const el = document.getElementById(id);
      if (el && s[key] != null) el.value = s[key];
    });
    const campusEl = document.getElementById('settingCampusAddress');
    if (campusEl && s.campus_address == null && s.contact_address != null) {
      campusEl.value = s.contact_address;
    }

    const toggleMap = [
      { id: 'toggleAcceptApplications', key: 'accept_applications' },
      { id: 'toggleEmailNotifications', key: 'email_notifications' },
      { id: 'toggleDashboardNotifications', key: 'dashboard_notifications' },
    ];
    toggleMap.forEach(({ id, key }) => {
      const el = document.getElementById(id);
      if (el) el.checked = s[key] === undefined || s[key] === null || s[key] === '1' || s[key] === 1 || s[key] === true;
    });

    // Total applications (read-only)
    if (totalEl && s.total_applications != null) totalEl.value = s.total_applications;

  } catch (e) {
    console.warn('Failed to load settings:', e);
    showToast('Could not load settings from server.');
  }
}

/* ─── SIDEBAR TOGGLE ─── */
function openSidebar() {
  document.getElementById('sidebar').classList.add('open');
  document.getElementById('sidebarOverlay').classList.add('open');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}

/* ─── NOTIFICATIONS ─── */
let notifications = [];
/** Refs user has marked as read or cleared (so we don't show them in the list) */
const readNotifRefs = new Set();
const knownNotifRefs = new Set();

function settingIsEnabled(value, defaultValue = true) {
  if (value === undefined || value === null || value === '') return defaultValue;
  return value === true || value === 1 || value === '1' || String(value).toLowerCase() === 'true';
}

function dashboardNotificationsEnabled() {
  if (SYSTEM_SETTINGS && SYSTEM_SETTINGS.dashboard_notifications !== undefined) {
    return settingIsEnabled(SYSTEM_SETTINGS.dashboard_notifications, true);
  }
  const toggle = document.getElementById('toggleDashboardNotifications');
  if (toggle) return toggle.checked;
  return true;
}

function getUnreadNotifications() {
  const pending = getApplications().filter(a => a.status === 'Pending');
  return pending.map(a => {
    const ref = a.ref || (a.id != null ? `app-${a.id}` : '');
    return {
      id: ref,
      appId: a.id != null ? Number(a.id) : null,
      type: 'pending',
      title: fullNameDisplay(a),
      program: shortProg(a.firstChoice),
      message: `Application pending review`,
      time: formatDate(a.filed),
      read: false,
      ref
    };
  });
}

function syncNotificationToasts(options = {}) {
  const silent = options.silent === true;
  const current = getUnreadNotifications().filter(n => n.ref);
  const fresh = current.filter(n => !knownNotifRefs.has(n.ref) && !readNotifRefs.has(n.ref));

  current.forEach(n => knownNotifRefs.add(n.ref));

  if (silent || !fresh.length || !dashboardNotificationsEnabled()) return;

  if (fresh.length === 1) {
    showToast(`New admin notification: ${fresh[0].title} has a pending application.`);
  } else {
    showToast(`Admin received ${fresh.length} new application notifications.`);
  }
}

function renderNotifications() {
  const all = getUnreadNotifications();
  notifications = all.filter(n => !readNotifRefs.has(n.ref));
  const body = document.getElementById('notifBody');
  const hasNotif = notifications.length > 0;
  const buttons = document.getElementById('markReadBtn').parentElement;

  if (hasNotif) {
    body.innerHTML = notifications.map(n => `
      <div class="notif-item notif-item--unread" data-app-id="${n.appId != null ? n.appId : ''}" data-app-ref="${escapeHtml(n.ref || '')}" role="button" tabindex="0">
        <div class="notif-content">
          <div class="notif-badge-dot"></div>
          <div class="notif-text">
            <div class="notif-msg"><strong>${escapeHtml(n.title)}</strong> — ${escapeHtml(n.message)}</div>
            <div class="notif-time">${escapeHtml(n.program)} • ${escapeHtml(n.time)}</div>
          </div>
        </div>
      </div>
    `).join('');
    buttons.querySelectorAll('.notif-btn').forEach(b => b.style.display = 'block');
  } else {
    body.innerHTML = `
      <div class="notif-empty">
        <i data-iconsax="bell-off" style="width:48px;height:48px;opacity:0.3;margin-bottom:16px"></i>
        <p>No new notifications</p>
      </div>
    `;
    buttons.querySelectorAll('.notif-btn').forEach(b => b.style.display = 'none');
  }
}

function toggleNotifications() {
  const panel = document.getElementById('notifPanel');
  const overlay = document.getElementById('notifOverlay');
  const isOpen = panel.classList.contains('open');

  if (isOpen) {
    panel.classList.remove('open');
    overlay.classList.remove('open');
  } else {
    renderNotifications();
    panel.classList.add('open');
    overlay.classList.add('open');
  }
}

function closeNotifications() {
  document.getElementById('notifPanel').classList.remove('open');
  document.getElementById('notifOverlay').classList.remove('open');
}

function updatePendingBadge() {
  const count = getUnreadNotifications().filter(n => !readNotifRefs.has(n.ref)).length;
  const badge = document.getElementById('pendingBadge');
  const dot = document.getElementById('notifDot');
  if (badge) {
    badge.textContent = count;
    badge.classList.toggle('has-pending', count > 0);
  }
  if (dot) dot.style.display = count > 0 ? '' : 'none';
}

function setPasswordChangeError(message) {
  const errorEl = document.getElementById('changePasswordError');
  if (!errorEl) return;
  errorEl.textContent = message || '';
  errorEl.style.display = message ? 'block' : 'none';
}

function clearPasswordFields() {
  ['currentAdminPassword', 'newAdminPassword', 'confirmAdminPassword'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.value = '';
  });
}

async function changeAdminPassword() {
  const currentPassword = document.getElementById('currentAdminPassword')?.value || '';
  const newPassword = document.getElementById('newAdminPassword')?.value || '';
  const confirmPassword = document.getElementById('confirmAdminPassword')?.value || '';
  const btn = document.getElementById('changePasswordBtn');

  setPasswordChangeError('');

  if (!currentPassword || !newPassword || !confirmPassword) {
    setPasswordChangeError('Please fill in all password fields.');
    return;
  }
  if (newPassword !== confirmPassword) {
    setPasswordChangeError('New password and confirmation do not match.');
    return;
  }
  if (newPassword.length < 8) {
    setPasswordChangeError('New password must be at least 8 characters.');
    return;
  }
  if (typeof AdmissionAPI === 'undefined' || !AdmissionAPI.getToken()) {
    setPasswordChangeError('Please log in again before changing your password.');
    return;
  }

  const originalHtml = btn ? btn.innerHTML : '';
  if (btn) {
    btn.disabled = true;
    btn.textContent = 'Saving...';
  }

  try {
    await AdmissionAPI.changePassword({
      current_password: currentPassword,
      password: newPassword,
      password_confirmation: confirmPassword,
    });
    clearPasswordFields();
    showToast('Password updated successfully.');
    const modal = document.getElementById('passwordUpdatedModal');
    if (modal) modal.style.display = 'flex';
  } catch (e) {
    const errors = e && e.data && e.data.errors ? e.data.errors : null;
    const firstError = errors ? Object.values(errors).flat()[0] : '';
    setPasswordChangeError(firstError || e.message || 'Failed to update password.');
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.innerHTML = originalHtml || '<i data-iconsax="save"></i> Save New Password';
      if (typeof iconsax !== 'undefined') iconsax.createIcons();
    }
  }
}

function closePasswordUpdatedModal() {
  const modal = document.getElementById('passwordUpdatedModal');
  if (modal) modal.style.display = 'none';
}

function logoutAfterPasswordChange() {
  if (typeof AdmissionAPI !== 'undefined') AdmissionAPI.clearToken();
  const form = document.getElementById('logoutForm');
  if (form) {
    form.submit();
    return;
  }
  window.location.href = ADMIN_LOGIN_URL;
}

function togglePasswordVisibility(button) {
  const input = document.getElementById(button.getAttribute('data-target') || '');
  if (!input) return;

  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
  button.innerHTML = `<i data-iconsax="${isHidden ? 'eye-off' : 'eye'}" style="width:18px;height:18px;"></i>`;
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
}

/* ─── EVENT LISTENERS ─── */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.nav-item[data-page]').forEach(link => {
    link.addEventListener('click', e => { e.preventDefault(); showPage(link.dataset.page); });
  });

  document.getElementById('menuToggle').addEventListener('click', openSidebar);
  document.getElementById('sidebarCloseBtn').addEventListener('click', closeSidebar);
  document.getElementById('sidebarOverlay').addEventListener('click', closeSidebar);
  document.getElementById('slideoverClose').addEventListener('click', closeSlideover);
  document.getElementById('slideoverOverlay').addEventListener('click', closeSlideover);

  const changePasswordBtn = document.getElementById('changePasswordBtn');
  if (changePasswordBtn) changePasswordBtn.addEventListener('click', changeAdminPassword);

  document.querySelectorAll('.password-toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => togglePasswordVisibility(btn));
  });

  const stayAfterPasswordBtn = document.getElementById('stayAfterPasswordBtn');
  if (stayAfterPasswordBtn) stayAfterPasswordBtn.addEventListener('click', closePasswordUpdatedModal);

  const logoutAfterPasswordBtn = document.getElementById('logoutAfterPasswordBtn');
  if (logoutAfterPasswordBtn) logoutAfterPasswordBtn.addEventListener('click', logoutAfterPasswordChange);

  initDeadlineField();

  const passwordUpdatedModal = document.getElementById('passwordUpdatedModal');
  if (passwordUpdatedModal) {
    passwordUpdatedModal.addEventListener('click', function (e) {
      if (e.target === passwordUpdatedModal) closePasswordUpdatedModal();
    });
  }

  // Delegated click: view application details (eye icon or "View") in Applications table
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-view-app');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const id = btn.getAttribute('data-app-id');
    const ref = (btn.getAttribute('data-app-ref') || '').trim();
    const numId = id != null && id !== '' && id !== 'null' ? Number(id) : NaN;
    if (!isNaN(numId) && numId > 0) {
      openSlideoverById(numId);
    } else if (ref) {
      openSlideover(ref);
    }
  });

  // Delegated click: delete application
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-delete-app');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();
    const id = btn.getAttribute('data-app-id');
    const ref = (btn.getAttribute('data-app-ref') || '').trim();
    const numId = id != null && id !== '' && id !== 'null' ? Number(id) : NaN;
    if (!isNaN(numId) && numId > 0) {
      deleteApplicationById(numId, ref);
    }
  });

  // Delegated click: notification items — open application details and close notification panel
  document.addEventListener('click', function (e) {
    const item = e.target.closest('.notif-item');
    if (!item) return;
    e.preventDefault();
    closeNotifications();
    const id = item.getAttribute('data-app-id');
    const ref = item.getAttribute('data-app-ref');
    if (id != null && id !== '' && id !== 'null' && !isNaN(Number(id))) {
      openSlideoverById(Number(id));
    } else if (ref) {
      openSlideover(ref);
    }
  });

  document.getElementById('notifBtn').addEventListener('click', toggleNotifications);
  document.getElementById('notifClose').addEventListener('click', closeNotifications);
  document.getElementById('notifOverlay').addEventListener('click', closeNotifications);
  document.getElementById('markReadBtn').addEventListener('click', () => {
    const list = getUnreadNotifications().filter(n => !readNotifRefs.has(n.ref));
    list.forEach(n => readNotifRefs.add(n.ref));
    renderNotifications();
    updatePendingBadge();
    showToast('All notifications marked as read');
  });
  document.getElementById('clearNotifBtn').addEventListener('click', () => {
    const list = getUnreadNotifications().filter(n => !readNotifRefs.has(n.ref));
    list.forEach(n => readNotifRefs.add(n.ref));
    renderNotifications();
    updatePendingBadge();
    closeNotifications();
    showToast('All notifications cleared');
  });

  document.getElementById('searchInput').addEventListener('input', applyFilters);
  document.getElementById('filterType').addEventListener('change', applyFilters);
  document.getElementById('filterStatus').addEventListener('change', applyFilters);
  document.getElementById('filterProgram').addEventListener('change', applyFilters);
  document.getElementById('clearFilters').addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterProgram').value = '';
    applyFilters();
  });

  document.getElementById('exportCsvBtn').addEventListener('click', () => exportCSV(filteredApps, 'btech_applications.xls'));
  document.getElementById('rptCsvBtn').addEventListener('click', () => exportReportsCSV());
  document.getElementById('viewAllBtn').addEventListener('click', () => showPage('applications'));

  document.getElementById('saveSettingsBtn').addEventListener('click', async () => {
    const sy = document.getElementById('settingSY')?.value || '';
    const deadline = getDeadlineFieldValue();
    const interviewSched = document.getElementById('settingInterviewSchedule')?.value || '';
    const instName = document.getElementById('settingInstitutionName')?.value || '';
    const admEmail = document.getElementById('settingAdmissionsEmail')?.value || '';
    const campusAddr = document.getElementById('settingCampusAddress')?.value || '';
    const acceptApps = document.getElementById('toggleAcceptApplications')?.checked ? '1' : '0';
    const emailNotif = document.getElementById('toggleEmailNotifications')?.checked ? '1' : '0';
    const dashboardNotif = document.getElementById('toggleDashboardNotifications')?.checked ? '1' : '0';
    const payload = {
      school_year: sy,
      application_deadline: deadline,
      interview_schedule_text: interviewSched,
      institution_name: instName,
      admissions_email: admEmail,
      campus_address: campusAddr,
      contact_address: campusAddr,
      accept_applications: acceptApps,
      email_notifications: emailNotif,
      dashboard_notifications: dashboardNotif,
    };

    const btn = document.getElementById('saveSettingsBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Saving…'; }

    try {
      if (typeof AdmissionAPI !== 'undefined' && AdmissionAPI.getToken()) {
        const updated = await AdmissionAPI.saveSettings(payload);
        applySystemSettingsUI(updated || payload);
        showToast('Settings saved successfully to database.');
      } else {
        showToast('Settings saved locally (not logged in to API).');
      }
      // Update immediately from local payload as fallback.
      applySystemSettingsUI(payload);
      refreshDataSoon();
    } catch (e) {
      showToast('Failed to save settings: ' + (e.message || 'Unknown error'));
      console.error('Save settings error:', e);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<i data-iconsax="save"></i> Save Changes';
        if (typeof iconsax !== 'undefined') iconsax.createIcons();
      }
    }
  });

  // Program Category Filters
  const progFilterPills = document.getElementById('progFilterPills');
  if (progFilterPills) {
    progFilterPills.addEventListener('click', e => {
      const pill = e.target.closest('.filter-pill');
      if (!pill) return;
      programFilter = pill.dataset.category;
      progFilterPills.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      renderProgramsTable();
    });
  }

  // Interview Category Filters
  const interviewFilterPills = document.getElementById('interviewFilterPills');
  if (interviewFilterPills) {
    interviewFilterPills.addEventListener('click', e => {
      const pill = e.target.closest('.filter-pill');
      if (!pill) return;
      interviewFilter = pill.dataset.category;
      interviewFilterPills.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      renderInterviewsTable();
    });
  }


  if (typeof AdmissionAPI !== 'undefined' && AdmissionAPI.getToken()) {
    // Show layout immediately
    showPage('dashboard');

    // Initial fetch
    refreshData(true);

    if (ADMIN_REFRESH_MS > 0) {
      setInterval(() => {
        refreshData(false);
      }, ADMIN_REFRESH_MS);
    }
  } else {
    if (typeof AdmissionAPI !== 'undefined') window.location.href = ADMIN_LOGIN_URL;
    else showPage('dashboard');
  }
});

/**
 * Fetches the latest data from the API and updates the UI.
 * @param {boolean} isInitial If true, redirects to dashboard after loading.
 */
async function refreshData(isInitial = false) {
  if (isRefreshingData) return;
  isRefreshingData = true;

  try {
    try {
      const me = await AdmissionAPI.getMe();
      const role = (me && (me.role || (me.user && me.user.role))) || '';
      if (role && role !== 'admin' && role !== 'staff') {
        if (typeof AdmissionAPI.clearToken === 'function') AdmissionAPI.clearToken();
        if (window.location.pathname !== ADMIN_LOGIN_URL) window.location.replace(ADMIN_LOGIN_URL);
        return;
      }
    } catch (err) {
      console.warn('getMe failed:', err);
      if (err && err.status === 401) {
        if (typeof AdmissionAPI.clearToken === 'function') AdmissionAPI.clearToken();
        if (window.location.pathname !== ADMIN_LOGIN_URL) window.location.replace(ADMIN_LOGIN_URL);
        return;
      }
    }

    // Force brand label in UI (requested)
    const name = 'Admissions Office';
    const roleLabel = 'Super Administration';
    const initials = name.split(/\s+/).map(s => (s[0] || '').toUpperCase()).slice(0, 2).join('') || '?';
    const sidebarName = document.getElementById('sidebarUserName');
    const sidebarRole = document.getElementById('sidebarUserRole');
    const sidebarInitials = document.getElementById('sidebarUserInitials');
    const topbarName = document.getElementById('topbarUserName');
    const topbarInitials = document.getElementById('topbarUserInitials');
    if (sidebarName) sidebarName.textContent = name;
    if (sidebarRole) sidebarRole.textContent = roleLabel;
    if (sidebarInitials) sidebarInitials.textContent = initials;
    if (topbarName) topbarName.textContent = name;
    if (topbarInitials) topbarInitials.textContent = initials;

    const [appsResult, programsResult, statsResult, settingsResult] = await Promise.allSettled([
      AdmissionAPI.getApplications({ per_page: 100 }),
      AdmissionAPI.getPrograms({ allowFallback: false }),
      AdmissionAPI.getDashboardStats(),
      AdmissionAPI.getSettings()
    ]);

    if (appsResult.status === 'rejected') console.warn('Applications API failed:', appsResult.reason);
    if (programsResult.status === 'rejected') console.warn('Programs API and fallback failed:', programsResult.reason);
    if (statsResult.status === 'rejected') console.warn('Dashboard stats API failed:', statsResult.reason);
    if (settingsResult.status === 'rejected') console.warn('Settings API failed:', settingsResult.reason);

    const res = appsResult.status === 'fulfilled' ? appsResult.value : null;
    const progList = programsResult.status === 'fulfilled' ? programsResult.value : [];
    const stats = statsResult.status === 'fulfilled' ? statsResult.value : null;
    const settings = settingsResult.status === 'fulfilled' ? settingsResult.value : {};

    DASHBOARD_STATS = stats && typeof stats === 'object' && Object.prototype.hasOwnProperty.call(stats, 'total_applications') ? stats : null;
    reportsRenderKey = '';
    applySystemSettingsUI(settings);

    const raw = Array.isArray(res) ? res : (res && Array.isArray(res.data)) ? res.data : [];
    API_APPLICATIONS = raw.map(a => {
      const lastName = (a.last_name != null ? String(a.last_name) : '').trim();
      const firstName = (a.first_name != null ? String(a.first_name) : '').trim();
      const middleName = (a.middle_name != null ? String(a.middle_name) : '').trim();
      const applicantName = (a.applicant_name || '').trim() || [firstName, middleName, lastName].filter(Boolean).join(' ');
      return {
        id: a.id != null ? Number(a.id) : null,
        programId: a.program_id != null ? Number(a.program_id) : (a.program && a.program.id != null ? Number(a.program.id) : null),
        ref: a.application_no || a.reference_number || '',
        applicant_name: applicantName,
        surname: lastName,
        firstName,
        middleName,
        firstChoice: a.first_choice || a.program_name || '',
        status: mapApiStatus(a.status || a.current_status),
        filed: a.submitted_at || a.created_at,
        type: a.applicant_type || a.application_type || 'Freshmen',
        g11: a.gwa_grade_11 != null ? a.gwa_grade_11 : (a.g11 || a.grade11GWA),
        g12: a.gwa_grade_12 != null ? a.gwa_grade_12 : (a.g12 || a.grade12GWA),
        pwd: a.pwd || a.differentlyAbled || 'No',
        solo: a.solo || a.soloParent || 'No',
        indigenous: a.indigenous || a.indigenous_person || a.indigenousPerson || 'No',
        fours: a.fours || a.fourPs || 'No',
        sex: a.gender || a.sex || '',
        raw: a
      };
    });
    filteredApps = [...API_APPLICATIONS];
    if (appsResult.status === 'rejected') {
      console.warn('Applications API failed:', appsResult.reason);
      window.LAST_API_ERROR = appsResult.reason?.message || 'API request failed';
      API_APPLICATIONS = 'error';
      filteredApps = [];
      renderApplicationsTable();
      return;
    }
    syncNotificationToasts({ silent: isInitial });

    if (programsResult.status === 'rejected') {
      window.LAST_PROGRAMS_API_ERROR = programsResult.reason?.message || 'Programs API request failed';
      API_PROGRAMS = [];
    } else {
      API_PROGRAMS = Array.isArray(progList) ? progList : [];
      window.LAST_PROGRAMS_API_ERROR = '';
    }
    
    // Initialize programEnabled state from the API's is_active field
    API_PROGRAMS.forEach(p => {
      programEnabled[p.name] = !!p.is_active;
    });

    if (isInitial) {
      if (currentPage === 'dashboard') initDashboard();
      else showPage(currentPage);
    } else {
      // Clear charts to force re-init on next show or immediate update if on dashboard
      if (trendChart) { trendChart.destroy(); trendChart = null; }
      if (typeChart) { typeChart.destroy(); typeChart = null; }
      if (programChart) { programChart.destroy(); programChart = null; }
      rerenderCurrentPageAfterRefresh();
    }
  } catch (err) {
    console.error('Refresh failed:', err);
    API_APPLICATIONS = [];
    if (isInitial) showPage('dashboard');
  } finally {
    isRefreshingData = false;
  }
}

/** Manual clear for server-side cache if things feel stuck */
async function manualClearCache() {
  const btn = document.getElementById('manualClearCacheBtn');
  if (!btn) return;
  
  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<i data-iconsax="loader"></i> Refreshing...';
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
  
  try {
    const res = await AdmissionAPI.clearPublicCache();
    showToast(res.message || 'Website cache refreshed! Guests will now see the latest changes.');
  } catch (err) {
    console.error('Manual clear failed:', err);
    showToast('Failed to refresh cache: ' + (err.message || 'Unknown error'), 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
  }
}

// Bind the button after DOM is ready (or via simple global)
document.addEventListener('click', function(e) {
  if (e.target && (e.target.id === 'manualClearCacheBtn' || e.target.closest('#manualClearCacheBtn'))) {
    manualClearCache();
  }
});
