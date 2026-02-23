/* ─── DATA ─── */
const PROGRAMS = [
  { name:'BS Information Technology', cat:'Technology', slots:60 },
  { name:'BS Business Administration Major in Marketing Management', cat:'Business', slots:50 },
  { name:'BS Business Administration Major in Financial Management', cat:'Business', slots:50 },
  { name:'BS Business Administration Major in Human Resource Management', cat:'Business', slots:50 },
  { name:'BS Entrepreneurship', cat:'Business', slots:40 },
  { name:'BS Economics', cat:'Business', slots:35 },
  { name:'BS Internal Auditing', cat:'Business', slots:35 },
  { name:'BS Accounting Information System', cat:'Business', slots:35 },
  { name:'BS Accountancy', cat:'Accountancy', slots:55 },
  { name:'BS Management Accounting', cat:'Accountancy', slots:40 },
  { name:'Bachelor of Arts in History', cat:'Arts & Sciences', slots:30 },
  { name:'BS Mathematics', cat:'Arts & Sciences', slots:30 },
  { name:'Bachelor of Elementary Education', cat:'Education', slots:45 },
  { name:'Bachelor of Secondary Education Major in English', cat:'Education', slots:45 },
  { name:'BS Hospitality Management', cat:'Hospitality', slots:50 },
  { name:'BS Tourism Management', cat:'Hospitality', slots:45 },
];

const MOCK_APPLICANTS = [
  { ref:'BTECH-2026-0001', surname:'Reyes',     firstName:'Jose',         type:'Freshmen',     firstChoice:'BS Information Technology', secondChoice:'BS Business Administration Major in Marketing Management', g11:88.5,g12:90.0, status:'Approved',            filed:'2026-01-05', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0002', surname:'Santos',    firstName:'Maria Clara',   type:'Freshmen',     firstChoice:'BS Accountancy', secondChoice:'BS Management Accounting', g11:95.2,g12:96.0, status:'Approved',            filed:'2026-01-08', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Pulilan',    presCity:'Pulilan'    },
  { ref:'BTECH-2026-0003', surname:'De Guzman', firstName:'Carlo',         type:'Transferee',   firstChoice:'BS Information Technology', secondChoice:'BS Entrepreneurship', g11:80.0,g12:82.5, status:'Interview Scheduled', filed:'2026-01-10', pwd:'No', solo:'Yes',fours:'No',  sex:'Male',   permCity:'Malolos',    presCity:'Malolos'    },
  { ref:'BTECH-2026-0004', surname:'Lim',       firstName:'Ana',           type:'Freshmen',     firstChoice:'Bachelor of Secondary Education Major in English', secondChoice:'Bachelor of Elementary Education', g11:91.0,g12:92.5, status:'Approved',            filed:'2026-01-12', pwd:'No', solo:'No', fours:'Yes', sex:'Female', permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0005', surname:'Cruz',      firstName:'Miguel',        type:'Freshmen',     firstChoice:'BS Hospitality Management', secondChoice:'BS Tourism Management', g11:83.0,g12:85.0, status:'Pending',             filed:'2026-01-15', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Bustos',     presCity:'Bustos'     },
  { ref:'BTECH-2026-0006', surname:'Bautista',  firstName:'Patricia',      type:'Freshmen',     firstChoice:'BS Business Administration Major in Financial Management', secondChoice:'BS Accountancy', g11:87.5,g12:89.0, status:'Pending',             filed:'2026-01-18', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Plaridel',   presCity:'Plaridel'   },
  { ref:'BTECH-2026-0007', surname:'Villanueva',firstName:'Ramon',         type:'ALS Graduate', firstChoice:'BS Entrepreneurship', secondChoice:'BS Business Administration Major in Marketing Management', g11:78.5,g12:80.0, status:'Interview Scheduled', filed:'2026-01-20', pwd:'Yes',solo:'No', fours:'No',  sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0008', surname:'Mendoza',   firstName:'Kristina',      type:'Freshmen',     firstChoice:'BS Accountancy', secondChoice:'BS Accounting Information System', g11:92.5,g12:94.0, status:'Approved',            filed:'2026-01-22', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Baliuag',    presCity:'Baliuag'    },
  { ref:'BTECH-2026-0009', surname:'Fernandez', firstName:'Luis',          type:'Returnee',     firstChoice:'BS Information Technology', secondChoice:'BS Entrepreneurship', g11:75.0,g12:77.0, status:'Pending',             filed:'2026-01-25', pwd:'No', solo:'Yes',fours:'Yes', sex:'Male',   permCity:'Baliwag',    presCity:'Quezon City'},
  { ref:'BTECH-2026-0010', surname:'Garcia',    firstName:'Sophia',        type:'Freshmen',     firstChoice:'BS Tourism Management', secondChoice:'BS Hospitality Management', g11:86.0,g12:88.0, status:'Approved',            filed:'2026-01-28', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Pandi',      presCity:'Pandi'      },
  { ref:'BTECH-2026-0011', surname:'Torres',    firstName:'Benedict',      type:'Freshmen',     firstChoice:'BS Mathematics', secondChoice:'BS Information Technology', g11:90.5,g12:91.0, status:'Rejected',            filed:'2026-02-02', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0012', surname:'Aquino',    firstName:'Diane',         type:'Transferee',   firstChoice:'BS Management Accounting', secondChoice:'BS Accountancy', g11:84.0,g12:85.5, status:'Interview Scheduled', filed:'2026-02-05', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'San Rafael', presCity:'San Rafael' },
  { ref:'BTECH-2026-0013', surname:'Dela Cruz', firstName:'Patrick',       type:'Freshmen',     firstChoice:'Bachelor of Elementary Education', secondChoice:'Bachelor of Secondary Education Major in English', g11:89.0,g12:90.5, status:'Approved',            filed:'2026-02-08', pwd:'No', solo:'No', fours:'Yes', sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0014', surname:'Pascual',   firstName:'Lara',          type:'Freshmen',     firstChoice:'BS Business Administration Major in Human Resource Management', secondChoice:'BS Entrepreneurship', g11:82.0,g12:83.5, status:'Pending',             filed:'2026-02-10', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Baliuag',    presCity:'Baliuag'    },
  { ref:'BTECH-2026-0015', surname:'Soriano',   firstName:'Jaime',         type:'ALS Graduate', firstChoice:'BS Hospitality Management', secondChoice:'BS Tourism Management', g11:76.0,g12:78.0, status:'Pending',             filed:'2026-02-14', pwd:'No', solo:'No', fours:'Yes', sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0016', surname:'Navarro',   firstName:'Angelica',      type:'Freshmen',     firstChoice:'BS Internal Auditing', secondChoice:'BS Accountancy', g11:88.0,g12:89.5, status:'Approved',            filed:'2026-02-17', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Pulilan',    presCity:'Pulilan'    },
  { ref:'BTECH-2026-0017', surname:'Reyes',     firstName:'Emmanuel',      type:'Returnee',     firstChoice:'BS Information Technology', secondChoice:'BS Mathematics', g11:80.0,g12:81.5, status:'Rejected',            filed:'2026-02-20', pwd:'Yes',solo:'No', fours:'No',  sex:'Male',   permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0018', surname:'Castillo',  firstName:'Francesca',     type:'Freshmen',     firstChoice:'BS Accountancy', secondChoice:'BS Management Accounting', g11:94.0,g12:95.5, status:'Interview Scheduled', filed:'2026-03-03', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Baliwag',    presCity:'Manila'     },
  { ref:'BTECH-2026-0019', surname:'Ramos',     firstName:'Andres',        type:'Freshmen',     firstChoice:'BS Economics', secondChoice:'BS Business Administration Major in Financial Management', g11:87.0,g12:88.5, status:'Approved',            filed:'2026-03-07', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Baliuag',    presCity:'Baliuag'    },
  { ref:'BTECH-2026-0020', surname:'Flores',    firstName:'Christine Joy',  type:'Freshmen',    firstChoice:'BS Tourism Management', secondChoice:'BS Hospitality Management', g11:85.5,g12:86.0, status:'Pending',             filed:'2026-03-10', pwd:'No', solo:'Yes',fours:'No',  sex:'Female', permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0021', surname:'Macapagal', firstName:'Rodrigo',       type:'Transferee',   firstChoice:'BS Business Administration Major in Marketing Management', secondChoice:'BS Entrepreneurship', g11:79.5,g12:81.0, status:'Pending',             filed:'2026-03-14', pwd:'No', solo:'No', fours:'Yes', sex:'Male',   permCity:'Guiguinto',  presCity:'Guiguinto'  },
  { ref:'BTECH-2026-0022', surname:'Tan',       firstName:'Melissa',       type:'Freshmen',     firstChoice:'BS Information Technology', secondChoice:'BS Mathematics', g11:93.0,g12:94.5, status:'Approved',            filed:'2026-03-18', pwd:'No', solo:'No', fours:'No',  sex:'Female', permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0023', surname:'Ocampo',    firstName:'Joshua',        type:'Freshmen',     firstChoice:'Bachelor of Arts in History', secondChoice:'Bachelor of Secondary Education Major in English', g11:83.5,g12:85.0, status:'Pending',             filed:'2026-03-22', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Bustos',     presCity:'Bustos'     },
  { ref:'BTECH-2026-0024', surname:'Perez',     firstName:'Catherine',     type:'ALS Graduate', firstChoice:'BS Entrepreneurship', secondChoice:'BS Business Administration Major in Marketing Management', g11:77.5,g12:79.0, status:'Interview Scheduled', filed:'2026-04-01', pwd:'No', solo:'Yes',fours:'Yes', sex:'Female', permCity:'Baliwag',    presCity:'Baliwag'    },
  { ref:'BTECH-2026-0025', surname:'Gonzales',  firstName:'Mark Anthony',  type:'Freshmen',     firstChoice:'BS Hospitality Management', secondChoice:'BS Tourism Management', g11:86.5,g12:87.0, status:'Pending',             filed:'2026-04-05', pwd:'No', solo:'No', fours:'No',  sex:'Male',   permCity:'Baliuag',    presCity:'Baliuag'    },
];

const programEnabled = {};
PROGRAMS.forEach(p => { programEnabled[p.name] = true; });

let currentPage = 'dashboard';
let appPage = 1;
const APP_PER_PAGE = 10;
let filteredApps = [...MOCK_APPLICANTS];
let trendChart, typeChart, programChart, gwaChart, eligChart;

/* ─── HELPERS ─── */
function avgGWA(app) { return ((app.g11 + app.g12) / 2).toFixed(1); }

function statusClass(status) {
  return { 'Pending':'badge--pending','Approved':'badge--approved','Rejected':'badge--rejected','Interview Scheduled':'badge--interview' }[status] || 'badge--pending';
}

function initials(app) { return (app.firstName[0] + app.surname[0]).toUpperCase(); }

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' });
}

function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

function shortProg(name) {
  const map = {
    'BS Information Technology':'BS InfoTech',
    'BS Accountancy':'BS Accountancy',
    'BS Management Accounting':'BS Mgmt Acctg',
    'BS Hospitality Management':'BS HM',
    'BS Tourism Management':'BS TM',
    'Bachelor of Elementary Education':'BEEd',
    'Bachelor of Secondary Education Major in English':'BSEd English',
    'Bachelor of Arts in History':'BA History',
    'BS Mathematics':'BS Math',
    'BS Entrepreneurship':'BS Entrep',
    'BS Economics':'BS Econ',
    'BS Internal Auditing':'BS Int. Auditing',
    'BS Accounting Information System':'BS AIS',
  };
  if (map[name]) return map[name];
  if (name.includes('Marketing'))     return 'BSBA Marketing';
  if (name.includes('Financial'))     return 'BSBA Finance';
  if (name.includes('Human Resource'))return 'BSBA HRM';
  return name.length > 22 ? name.slice(0,22)+'…' : name;
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

  const labels = { dashboard:'Dashboard', applications:'Applications', programs:'Programs', reports:'Reports', settings:'Settings' };
  document.getElementById('breadcrumbPage').textContent = labels[page] || page;

  if (page === 'dashboard')    initDashboard();
  if (page === 'applications') renderApplicationsTable();
  if (page === 'programs')     renderProgramsTable();
  if (page === 'reports')      initReports();
  if (page === 'settings')     initSettings();

  if (window.innerWidth < 768) closeSidebar();
}

/* ─── DASHBOARD ─── */
function initDashboard() {
  renderKPIs();
  renderRecentList();
  initCharts();
}

function renderKPIs() {
  const total     = MOCK_APPLICANTS.length;
  const pending   = MOCK_APPLICANTS.filter(a => a.status === 'Pending').length;
  const approved  = MOCK_APPLICANTS.filter(a => a.status === 'Approved').length;
  const rejected  = MOCK_APPLICANTS.filter(a => a.status === 'Rejected').length;
  const interview = MOCK_APPLICANTS.filter(a => a.status === 'Interview Scheduled').length;

  const kpis = [
    { label:'Total Applications',    value:total,     iconPath:'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17', cls:'kpi-icon--navy',  delta:'↑ 12 vs last week', dc:'up' },
    { label:'Pending Review',        value:pending,   iconPath:'M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0-5a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm0-8v5', cls:'kpi-icon--gold',  delta:`${interview} in interview`, dc:'warn' },
    { label:'Approved',              value:approved,  iconPath:'M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01', cls:'kpi-icon--green', delta:`${Math.round(approved/total*100)}% acceptance rate`, dc:'up' },
    { label:'Interview Scheduled',   value:interview, iconPath:'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm3 10l2 2 4-4', cls:'kpi-icon--blue',  delta:'Awaiting results', dc:'' },
    { label:'Rejected',              value:rejected,  iconPath:'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z', cls:'kpi-icon--red',   delta:`${Math.round(rejected/total*100)}% rejection rate`, dc:'' },
  ];

  document.getElementById('kpiGrid').innerHTML = kpis.map(k => `
    <div class="kpi-card">
      <div class="kpi-icon ${k.cls}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="${k.iconPath}"/></svg>
      </div>
      <div class="kpi-body">
        <div class="kpi-value">${k.value}</div>
        <div class="kpi-label">${k.label}</div>
        <div class="kpi-delta ${k.dc}">${k.delta}</div>
      </div>
    </div>
  `).join('');

  document.getElementById('pendingBadge').textContent = pending;
  document.getElementById('notifDot').style.display = pending > 0 ? '' : 'none';
}

function renderRecentList() {
  const recent = [...MOCK_APPLICANTS].sort((a,b) => new Date(b.filed) - new Date(a.filed)).slice(0,5);
  document.getElementById('recentList').innerHTML = recent.map(app => `
    <div class="recent-item" onclick="openSlideover('${app.ref}')">
      <div class="recent-avatar">${initials(app)}</div>
      <div class="recent-info">
        <div class="recent-name">${app.surname}, ${app.firstName}</div>
        <div class="recent-program">${shortProg(app.firstChoice)}</div>
      </div>
      <span class="badge ${statusClass(app.status)}">${app.status}</span>
    </div>
  `).join('');
}

function initCharts() {
  Chart.defaults.font.family = "'DM Sans', sans-serif";
  Chart.defaults.color = '#64748b';
  const NAVY2 = '#254d82', GOLD = '#c9933a', GREEN = '#16a34a', BLUE = '#2563eb', NAVY = '#1b3557';

  // Trend
  if (trendChart) trendChart.destroy();
  trendChart = new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr'],
      datasets: [{ label:'Applications', data:[7,8,7,3], borderColor:NAVY2, backgroundColor:'rgba(37,77,130,.08)', fill:true, tension:.42, pointBackgroundColor:NAVY2, pointRadius:5, pointHoverRadius:7, borderWidth:2.5 }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true,ticks:{stepSize:2},grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
  });

  // Donut
  const typeCounts = {};
  MOCK_APPLICANTS.forEach(a => { typeCounts[a.type] = (typeCounts[a.type]||0)+1; });
  const typeColors = [NAVY, GOLD, GREEN, BLUE];
  if (typeChart) typeChart.destroy();
  typeChart = new Chart(document.getElementById('typeChart'), {
    type:'doughnut',
    data:{ labels:Object.keys(typeCounts), datasets:[{ data:Object.values(typeCounts), backgroundColor:typeColors, borderWidth:2, borderColor:'#fff', hoverOffset:6 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, cutout:'68%' }
  });
  document.getElementById('donutLegend').innerHTML = Object.keys(typeCounts).map((k,i) => `
    <div class="legend-item"><div class="legend-dot" style="background:${typeColors[i]}"></div><span>${k} (${typeCounts[k]})</span></div>
  `).join('');

  // Program bar
  const progCounts = {};
  MOCK_APPLICANTS.forEach(a => { progCounts[a.firstChoice] = (progCounts[a.firstChoice]||0)+1; });
  const sorted = Object.entries(progCounts).sort((a,b)=>b[1]-a[1]).slice(0,6);
  if (programChart) programChart.destroy();
  programChart = new Chart(document.getElementById('programChart'), {
    type:'bar',
    data:{ labels:sorted.map(([k])=>shortProg(k)), datasets:[{ label:'Applicants', data:sorted.map(([,v])=>v), backgroundColor:NAVY2, borderRadius:6, borderSkipped:false }] },
    options:{ responsive:true, maintainAspectRatio:false, indexAxis:'y', plugins:{legend:{display:false}}, scales:{ x:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}}, y:{grid:{display:false}} } }
  });
}

/* ─── APPLICATIONS TABLE ─── */
function renderApplicationsTable() {
  const sel = document.getElementById('filterProgram');
  if (sel.options.length <= 1) {
    PROGRAMS.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.name; opt.textContent = shortProg(p.name);
      sel.appendChild(opt);
    });
  }
  applyFilters();
}

function applyFilters() {
  const search  = document.getElementById('searchInput').value.toLowerCase();
  const type    = document.getElementById('filterType').value;
  const status  = document.getElementById('filterStatus').value;
  const program = document.getElementById('filterProgram').value;

  filteredApps = MOCK_APPLICANTS.filter(a => {
    const fullName = `${a.surname} ${a.firstName}`.toLowerCase();
    return (!search  || fullName.includes(search) || a.ref.toLowerCase().includes(search))
        && (!type    || a.type === type)
        && (!status  || a.status === status)
        && (!program || a.firstChoice === program);
  });
  appPage = 1;
  renderTable();
  renderPagination();
}

function renderTable() {
  const start = (appPage-1)*APP_PER_PAGE;
  const slice = filteredApps.slice(start, start+APP_PER_PAGE);
  const tbody = document.getElementById('appTableBody');

  if (!slice.length) {
    tbody.innerHTML = `<tr><td colspan="8"><div class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
      <p>No applications match your filters.</p>
    </div></td></tr>`;
    document.getElementById('tableInfo').textContent = 'No results found';
    return;
  }

  tbody.innerHTML = slice.map(app => `
    <tr>
      <td class="ref-col">${app.ref}</td>
      <td class="name-col">${app.surname}, ${app.firstName}</td>
      <td>${app.type}</td>
      <td>${shortProg(app.firstChoice)}</td>
      <td class="gwa-col">${avgGWA(app)}</td>
      <td>${formatDate(app.filed)}</td>
      <td><span class="badge ${statusClass(app.status)}">${app.status}</span></td>
      <td>
        <div class="action-btns-cell">
          <button class="btn-icon btn-icon--view" title="View Details" onclick="openSlideover('${app.ref}')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
          <select class="status-select" onchange="changeStatus('${app.ref}', this.value)">
            ${['Pending','Interview Scheduled','Approved','Rejected'].map(s =>
              `<option value="${s}" ${app.status===s?'selected':''}>${s}</option>`
            ).join('')}
          </select>
        </div>
      </td>
    </tr>
  `).join('');

  document.getElementById('tableInfo').textContent =
    `Showing ${start+1}–${Math.min(start+APP_PER_PAGE,filteredApps.length)} of ${filteredApps.length} applications`;
}

function renderPagination() {
  const total = Math.ceil(filteredApps.length / APP_PER_PAGE);
  const pg = document.getElementById('pagination');
  if (total <= 1) { pg.innerHTML = ''; return; }
  let html = '';
  for (let i = 1; i <= total; i++)
    html += `<button class="page-btn ${i===appPage?'active':''}" onclick="goToPage(${i})">${i}</button>`;
  pg.innerHTML = html;
}

function goToPage(n) { appPage = n; renderTable(); renderPagination(); }

function changeStatus(ref, newStatus) {
  const app = MOCK_APPLICANTS.find(a => a.ref === ref);
  if (app) {
    app.status = newStatus;
    showToast(`Status updated to "${newStatus}" for ${app.surname}, ${app.firstName}`);
    renderKPIs();
    renderRecentList();
    applyFilters();
  }
}

function exportCSV(data, filename) {
  const headers = ['Ref No.','Surname','First Name','Type','1st Choice','2nd Choice','G11 GWA','G12 GWA','Avg GWA','Status','Date Filed','PWD','Solo Parent','4Ps'];
  const rows = data.map(a =>
    [a.ref,a.surname,a.firstName,a.type,a.firstChoice,a.secondChoice,a.g11,a.g12,avgGWA(a),a.status,a.filed,a.pwd,a.solo,a.fours]
    .map(v => `"${v}"`).join(',')
  );
  const csv = [headers.join(','),...rows].join('\n');
  const blob = new Blob([csv],{type:'text/csv'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href=url; a.download=filename||'applications.csv'; a.click();
  URL.revokeObjectURL(url);
  showToast('CSV exported successfully');
}

/* ─── SLIDE-OVER ─── */
function openSlideover(ref) {
  const app = MOCK_APPLICANTS.find(a => a.ref === ref);
  if (!app) return;

  const dateObj = new Date(app.filed);
  const dobObj = new Date('2003-12-15'); // Sample DOB - adjust as needed

  document.getElementById('slideoverTitle').textContent = `${app.firstName} ${app.surname}`;
  document.getElementById('slideoverRef').textContent = app.ref;

  document.getElementById('slideoverBody').innerHTML = `
    <div class="app-form">
      <!-- HEADER WITH PHOTO -->
      <div class="form-header-with-photo">
        <div>
          <div class="form-college">BALIWAG POLYTECHNIC COLLEGE</div>
          <div class="form-subtitle">College Admission Application</div>
        </div>
        <div class="form-photo-box">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
          <div class="form-photo-label">2x2 Photo</div>
        </div>
      </div>

      <!-- REFERENCE & DATE -->
      <div class="form-row-header">
        <div class="form-ref-box">
          <div class="form-label">REFERENCE NO.</div>
          <div class="form-value">${app.ref}</div>
        </div>
        <div class="form-date-box">
          <div class="form-label">DATE FILED</div>
          <div class="form-value">${formatDate(app.filed)}</div>
        </div>
      </div>

      <!-- SECTION I: PERSONAL INFORMATION -->
      <div class="form-section">
        <div class="form-section-title">SECTION I — APPLICANT'S PERSONAL INFORMATION</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Surname</label>
            <div class="form-field-value">${app.surname}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">First Name</label>
            <div class="form-field-value">${app.firstName}</div>
          </div>

        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Middle Name</label>
            <div class="form-field-value">N/A</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Suffix</label>
            <div class="form-field-value">N/A</div>
          </div>

        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Date of Birth</label>
            <div class="form-field-value">${formatDate('2003-12-15')}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Place of Birth</label>
            <div class="form-field-value">${app.permCity}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Sex</label>
            <div class="form-field-value" style="font-size:13px">
              <span class="form-checkbox">${app.sex === 'Male' ? '☑️' : '☐'} Male</span>
              <span class="form-checkbox">${app.sex === 'Female' ? '☑️' : '☐'} Female</span>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION II: CONTACT DETAILS -->
      <div class="form-section">
        <div class="form-section-title">SECTION II — CONTACT INFORMATION</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Student Contact Number</label>
            <div class="form-field-value">(+63) 9XX-XXX-XXXX</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Email Address</label>
            <div class="form-field-value">student@email.com</div>
          </div>
        </div>
        <div class="form-grid-1">
          <div class="form-field">
            <label class="form-field-label">Permanent Address</label>
            <div class="form-field-value">${app.permCity}, Bulacan</div>
          </div>
        </div>
        <div class="form-grid-1">
          <div class="form-field">
            <label class="form-field-label">Present Address</label>
            <div class="form-field-value">${app.presCity}, Bulacan</div>
          </div>
        </div>
      </div>

      <!-- SECTION III: FAMILY & CONTACT INFORMATION -->
      <div class="form-section">
        <div class="form-section-title">SECTION III — FAMILY & CONTACT INFORMATION</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Father's Full Name</label>
            <div class="form-field-value">N/A</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Father's Contact</label>
            <div class="form-field-value">N/A</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Mother's Full Name</label>
            <div class="form-field-value">N/A</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Mother's Contact</label>
            <div class="form-field-value">N/A</div>
          </div>
        </div>
      </div>

      <!-- SECTION IV: ACADEMIC BACKGROUND -->
      <div class="form-section">
        <div class="form-section-title">SECTION IV — ACADEMIC BACKGROUND</div>
        ${app.type === 'Freshmen' ? `
          <p class="form-subsection-label">Elementary School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">High School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">Senior High School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
        ` : app.type === 'Transferee' ? `
          <p class="form-subsection-label">Elementary School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">High School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">Senior High School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">Tertiary (College/University)</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
        ` : app.type === 'ALS Graduate' ? `
          <p class="form-subsection-label">Elementary School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">ALS (Alternative Learning System)</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">Learning Center Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Completed</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">A&E Certificate Number</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Test Result/Rating</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
        ` : app.type === 'Returnee' ? `
          <p class="form-subsection-label">Elementary School</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">School Name</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Year Graduated</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <p class="form-subsection-label">BTECH Previous Academic Record</p>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">Last Program Enrolled</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Last Year Attended</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-field">
              <label class="form-field-label">Student ID Number</label>
              <div class="form-field-value">N/A</div>
            </div>
            <div class="form-field">
              <label class="form-field-label">Reason for Return</label>
              <div class="form-field-value">N/A</div>
            </div>
          </div>
        ` : ''}
      </div>

      <!-- SECTION V: COURSE PREFERENCE & GWA -->
      <div class="form-section">
        <div class="form-section-title">SECTION V — COURSE PREFERENCE & GWA</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">1st Course Choice</label>
            <div class="form-field-value" style="font-size:12px">${shortProg(app.firstChoice)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">2nd Course Choice</label>
            <div class="form-field-value" style="font-size:12px">${shortProg(app.secondChoice)}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Grade 11 GWA</label>
            <div class="form-field-value">${app.g11}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Grade 12 GWA (1st Sem)</label>
            <div class="form-field-value">${app.g12}</div>
          </div>
        </div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Average GWA</label>
            <div class="form-field-value" style="color:var(--navy-mid);font-weight:700">${avgGWA(app)}</div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Academic Standing</label>
            <div class="form-field-value">${parseFloat(avgGWA(app))>=90?'With Honors':parseFloat(avgGWA(app))>=85?'Good Standing':'Regular'}</div>
          </div>
        </div>
      </div>

      <!-- SECTION VI: APPLICANT CLASSIFICATION -->
      <div class="form-section">
        <div class="form-section-title">SECTION VI — APPLICANT CLASSIFICATION</div>
        <div class="form-grid-2">
          <div class="form-field">
            <label class="form-field-label">Applicant Type</label>
            <div class="form-field-value">${app.type}</div>
          </div>
        </div>
        <div class="form-grid-3">
          <div class="form-field">
            <label class="form-field-label">Person with Disability (PWD)</label>
            <div class="form-field-value">
              <span class="form-checkbox">${app.pwd === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${app.pwd === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">Solo Parent</label>
            <div class="form-field-value">
              <span class="form-checkbox">${app.solo === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${app.solo === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-field-label">4Ps Beneficiary</label>
            <div class="form-field-value">
              <span class="form-checkbox">${app.fours === 'Yes' ? '☑️' : '☐'} YES</span>
              <span class="form-checkbox">${app.fours === 'No' ? '☑️' : '☐'} NO</span>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION VII: STATUS & ACTIONS -->
      <div class="form-section">
        <div class="form-section-title">APPLICATION STATUS</div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
          <span class="badge ${statusClass(app.status)}" id="slideoverBadge" style="font-size:13px;padding:6px 14px;font-weight:600">${app.status}</span>
          <select class="form-status-select" onchange="changeStatus('${app.ref}',this.value);document.getElementById('slideoverBadge').className='badge '+statusClass(this.value);document.getElementById('slideoverBadge').textContent=this.value">
            ${['Pending','Interview Scheduled','Approved','Rejected'].map(s=>
              `<option value="${s}" ${app.status===s?'selected':''}>${s}</option>`
            ).join('')}
          </select>
        </div>
      </div>
    </div>
  `;

  document.getElementById('slideover').classList.add('open');
  document.getElementById('slideoverOverlay').classList.add('open');
}

function closeSlideover() {
  document.getElementById('slideover').classList.remove('open');
  document.getElementById('slideoverOverlay').classList.remove('open');
}

/* ─── PROGRAMS TABLE ─── */
function renderProgramsTable() {
  const progCounts = {};
  MOCK_APPLICANTS.forEach(a => { progCounts[a.firstChoice] = (progCounts[a.firstChoice]||0)+1; });

  document.getElementById('programsTableBody').innerHTML = PROGRAMS.map(p => {
    const count = progCounts[p.name] || 0;
    const enabled = programEnabled[p.name];
    const isHosp = p.cat === 'Hospitality';
    const slotsLeft = p.slots - count;
    return `<tr>
      <td style="font-weight:600">${p.name}</td>
      <td><span class="badge" style="background:var(--navy-pale);color:var(--navy-mid)">${p.cat}</span></td>
      <td>4 Years</td>
      <td>${isHosp ? 'Day' : 'Day / Evening'}</td>
      <td style="font-weight:700;color:var(--navy-mid)">${count}</td>
      <td>${slotsLeft < 10 ? `<span style="color:var(--red);font-weight:700">${slotsLeft}</span>` : slotsLeft}</td>
      <td>
        <button class="prog-toggle ${enabled?'on':'off'}" onclick="toggleProgram('${p.name.replace(/'/g,"\\'")}', this)">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="1" y="5" width="22" height="14" rx="7"/><circle cx="${enabled?17:7}" cy="12" r="3" fill="currentColor" stroke="none"/></svg>
          ${enabled ? 'Active' : 'Disabled'}
        </button>
      </td>
    </tr>`;
  }).join('');
}

function toggleProgram(name, btn) {
  programEnabled[name] = !programEnabled[name];
  const on = programEnabled[name];
  btn.className = `prog-toggle ${on?'on':'off'}`;
  btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="1" y="5" width="22" height="14" rx="7"/><circle cx="${on?17:7}" cy="12" r="3" fill="currentColor" stroke="none"/></svg> ${on?'Active':'Disabled'}`;
  showToast(`"${shortProg(name)}" is now ${on?'Active':'Disabled'}`);
}

/* ─── REPORTS ─── */
function initReports() {
  const total     = MOCK_APPLICANTS.length;
  const approved  = MOCK_APPLICANTS.filter(a => a.status==='Approved').length;
  const avgGWAAll = (MOCK_APPLICANTS.reduce((s,a)=>s+(a.g11+a.g12)/2,0)/total).toFixed(2);
  const pwdCount  = MOCK_APPLICANTS.filter(a => a.pwd==='Yes').length;
  const foursCount= MOCK_APPLICANTS.filter(a => a.fours==='Yes').length;

  document.getElementById('reportsGrid').innerHTML = [
    { label:'Total Applications',   value:total,     sub:'S.Y. 2025–2026' },
    { label:'Overall Approval Rate',value:Math.round(approved/total*100)+'%', sub:`${approved} approved` },
    { label:'Overall Average GWA',  value:avgGWAAll, sub:'Across all applicants' },
    { label:'PWD Applicants',       value:pwdCount,  sub:'With disability' },
    { label:'4Ps Beneficiaries',    value:foursCount,sub:'Pantawid recipients' },
    { label:'Programs Offered',     value:PROGRAMS.length, sub:'5 categories' },
  ].map(s => `
    <div class="report-stat-card">
      <div class="report-stat-label">${s.label}</div>
      <div class="report-stat-value">${s.value}</div>
      <div class="report-stat-sub">${s.sub}</div>
    </div>
  `).join('');

  renderReportTable();
  initReportCharts();
}

function renderReportTable() {
  const progCounts={}, progStatuses={}, progGWAs={};
  MOCK_APPLICANTS.forEach(a => {
    const p = a.firstChoice;
    progCounts[p]=(progCounts[p]||0)+1;
    progStatuses[p]=progStatuses[p]||{Approved:0,Pending:0,Rejected:0,'Interview Scheduled':0};
    progStatuses[p][a.status]++;
    progGWAs[p]=progGWAs[p]||[];
    progGWAs[p].push((a.g11+a.g12)/2);
  });
  document.getElementById('rptTableBody').innerHTML = Object.entries(progCounts).sort((a,b)=>b[1]-a[1]).map(([name,count])=>{
    const st=progStatuses[name];
    const gwa=(progGWAs[name].reduce((s,v)=>s+v,0)/progGWAs[name].length).toFixed(1);
    const prog=PROGRAMS.find(p=>p.name===name);
    return `<tr>
      <td style="font-weight:600">${name}</td>
      <td>${prog?.cat||'—'}</td>
      <td style="font-weight:700">${count}</td>
      <td style="color:var(--green);font-weight:600">${st.Approved||0}</td>
      <td style="color:var(--yellow);font-weight:600">${(st.Pending||0)+(st['Interview Scheduled']||0)}</td>
      <td style="color:var(--red);font-weight:600">${st.Rejected||0}</td>
      <td style="font-weight:700;color:var(--navy-mid)">${gwa}</td>
    </tr>`;
  }).join('');
}

function initReportCharts() {
  const progGWAs={};
  MOCK_APPLICANTS.forEach(a=>{
    progGWAs[a.firstChoice]=progGWAs[a.firstChoice]||[];
    progGWAs[a.firstChoice].push((a.g11+a.g12)/2);
  });
  const sorted=Object.entries(progGWAs).map(([k,v])=>({ name:shortProg(k), avg:v.reduce((s,x)=>s+x,0)/v.length })).sort((a,b)=>b.avg-a.avg).slice(0,8);

  if (gwaChart) gwaChart.destroy();
  gwaChart = new Chart(document.getElementById('gwaChart'),{
    type:'bar',
    data:{ labels:sorted.map(s=>s.name), datasets:[{ label:'Avg GWA', data:sorted.map(s=>+s.avg.toFixed(1)), backgroundColor:'#254d82', borderRadius:6, borderSkipped:false }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ y:{min:70,max:100,grid:{color:'rgba(0,0,0,.05)'}}, x:{grid:{display:false}} } }
  });

  const pwd=MOCK_APPLICANTS.filter(a=>a.pwd==='Yes').length;
  const solo=MOCK_APPLICANTS.filter(a=>a.solo==='Yes').length;
  const fours=MOCK_APPLICANTS.filter(a=>a.fours==='Yes').length;
  const none=MOCK_APPLICANTS.length-pwd-solo-fours;
  if (eligChart) eligChart.destroy();
  eligChart = new Chart(document.getElementById('eligChart'),{
    type:'doughnut',
    data:{ labels:['No Special Classification','Solo Parent','4Ps','PWD'], datasets:[{ data:[none,solo,fours,pwd], backgroundColor:['#1b3557','#c9933a','#16a34a','#2563eb'], borderWidth:2, borderColor:'#fff', hoverOffset:6 }] },
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom',labels:{boxWidth:12,padding:10,font:{size:11}}}}, cutout:'60%' }
  });
}

/* ─── SETTINGS ─── */
function initSettings() {
  document.getElementById('settingTotal').value = MOCK_APPLICANTS.length;
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

function getUnreadNotifications() {
  const pending = MOCK_APPLICANTS.filter(a => a.status === 'Pending');
  return pending.map(a => ({
    id: a.ref,
    type: 'pending',
    title: `${a.surname}, ${a.firstName}`,
    program: shortProg(a.firstChoice),
    message: `Application pending review`,
    time: formatDate(a.filed),
    read: false,
    ref: a.ref
  }));
}

function renderNotifications() {
  notifications = getUnreadNotifications();
  const body = document.getElementById('notifBody');
  const hasNotif = notifications.length > 0;
  const buttons = document.getElementById('markReadBtn').parentElement;
  
  if (hasNotif) {
    body.innerHTML = notifications.map(n => `
      <div class="notif-item notif-item--unread" onclick="openSlideover('${n.ref}')">
        <div class="notif-content">
          <div class="notif-badge-dot"></div>
          <div class="notif-text">
            <div class="notif-msg"><strong>${n.title}</strong> — ${n.message}</div>
            <div class="notif-time">${n.program} • ${n.time}</div>
          </div>
        </div>
      </div>
    `).join('');
    buttons.querySelectorAll('.notif-btn').forEach(b => b.style.display = 'block');
  } else {
    body.innerHTML = `
      <div class="notif-empty">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
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

  document.getElementById('notifBtn').addEventListener('click', toggleNotifications);
  document.getElementById('notifClose').addEventListener('click', closeNotifications);
  document.getElementById('notifOverlay').addEventListener('click', closeNotifications);
  document.getElementById('markReadBtn').addEventListener('click', () => {
    notifications.forEach(n => n.read = true);
    renderNotifications();
    showToast('All notifications marked as read');
  });
  document.getElementById('clearNotifBtn').addEventListener('click', () => {
    notifications = [];
    renderNotifications();
    closeNotifications();
    showToast('All notifications cleared');
  });

  document.getElementById('searchInput').addEventListener('input', applyFilters);
  document.getElementById('filterType').addEventListener('change', applyFilters);
  document.getElementById('filterStatus').addEventListener('change', applyFilters);
  document.getElementById('filterProgram').addEventListener('change', applyFilters);
  document.getElementById('clearFilters').addEventListener('click', () => {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterType').value  = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterProgram').value = '';
    applyFilters();
  });

  document.getElementById('exportCsvBtn').addEventListener('click', () => exportCSV(filteredApps, 'btech_applications.csv'));
  document.getElementById('rptCsvBtn').addEventListener('click', () => exportCSV(MOCK_APPLICANTS, 'btech_report.csv'));
  document.getElementById('viewAllBtn').addEventListener('click', () => showPage('applications'));

  document.getElementById('saveSettingsBtn').addEventListener('click', () => {
    const sy = document.getElementById('settingSY').value;
    document.getElementById('topbarSY').textContent = sy;
    showToast('Settings saved successfully');
  });

  showPage('dashboard');
});