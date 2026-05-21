(function () {
  const apiBase = (window.ADMISSION_API_BASE || (window.location.origin + '/api')).replace(/\/+$/, '');
  const SESSION_KEY = 'applicant_portal_token';
  const ACTIVITY_KEY = 'applicant_portal_last_activity';
  const IDLE_LIMIT_MS = 60 * 60 * 1000;

  const state = {
    ref: '',
    email: '',
    token: '',
    payload: null,
    editing: false,
    sendingOtp: false,
    verifyingOtp: false,
    otpReady: true,
    resendSeconds: 0,
    resendTimer: null,
    idleTimer: null,
  };

  function now() {
    return Date.now();
  }

  function readStoredToken() {
    const token = sessionStorage.getItem(SESSION_KEY) || '';
    const lastActivity = Number(sessionStorage.getItem(ACTIVITY_KEY) || 0);
    if (!token || !lastActivity || now() - lastActivity > IDLE_LIMIT_MS) {
      clearStoredSession();
      return '';
    }
    return token;
  }

  function rememberSession(token) {
    if (!token) return;
    sessionStorage.setItem(SESSION_KEY, token);
    sessionStorage.setItem(ACTIVITY_KEY, String(now()));
  }

  function touchSession() {
    if (!state.token) return;
    sessionStorage.setItem(ACTIVITY_KEY, String(now()));
  }

  function clearStoredSession() {
    sessionStorage.removeItem(SESSION_KEY);
    sessionStorage.removeItem(ACTIVITY_KEY);
  }

  function resetPortalSession(message) {
    clearStoredSession();
    state.token = '';
    state.payload = null;
    state.editing = false;
    state.otpReady = true;
    $('lookupForm')?.reset();
    $('otpForm')?.reset();
    showStep('lookupStep');
    if (message) toast(message, 'warning');
  }

  const fields = [
    {
      title: 'Program Choices',
      items: [
        ['first_choice', 'First Choice Program', 'select:first'],
        ['second_choice', 'Second Choice Program', 'select:second'],
        ['applicant_type', 'Applicant Type', 'text'],
      ],
    },
    {
      title: 'Personal Information',
      items: [
        ['last_name', 'Last Name', 'text'],
        ['first_name', 'First Name', 'text'],
        ['middle_name', 'Middle Name', 'text'],
        ['suffix', 'Suffix', 'text'],
        ['date_of_birth', 'Date of Birth', 'date'],
        ['place_of_birth', 'Place of Birth', 'text'],
        ['sex', 'Sex', 'text'],
        ['civil_status', 'Civil Status', 'text'],
        ['email', 'Email Address', 'email'],
        ['contact_number', 'Contact Number', 'text'],
      ],
    },
    {
      title: 'Address',
      items: [
        ['permanent_address', 'Permanent Address', 'textarea'],
        ['present_address', 'Present Address', 'textarea'],
      ],
    },
    {
      title: 'Family Background',
      items: [
        ['father_name', 'Father Name', 'text'],
        ['father_suffix', 'Father Suffix', 'text'],
        ['father_contact', 'Father Contact', 'text'],
        ['mother_name', 'Mother Name', 'text'],
        ['mother_contact', 'Mother Contact', 'text'],
      ],
    },
    {
      title: 'Education',
      items: [
        ['elementary_school', 'Elementary School', 'text'],
        ['elementary_year_graduated', 'Elementary Year Graduated', 'number'],
        ['junior_high_school', 'Junior High School', 'text'],
        ['junior_high_year_graduated', 'Junior High Year Graduated', 'number'],
        ['senior_high_school', 'Senior High School', 'text'],
        ['senior_high_strand', 'Senior High Strand', 'text'],
        ['senior_high_year_graduated', 'Senior High Year Graduated', 'number'],
        ['previous_college', 'Previous College', 'text'],
        ['previous_college_program', 'Previous College Program', 'text'],
        ['previous_college_year_last_attended', 'Previous College Year Last Attended', 'number'],
        ['gwa_grade_11', 'Grade 11 GWA', 'number'],
        ['gwa_grade_12', 'Grade 12 GWA', 'number'],
      ],
    },
    {
      title: 'Other Details',
      items: [
        ['pwd', 'PWD', 'text'],
        ['solo_parent', 'Solo Parent', 'text'],
        ['indigenous', 'Indigenous', 'text'],
        ['four_ps', '4Ps', 'text'],
        ['academic_year', 'Academic Year', 'text'],
        ['semester', 'Semester', 'text'],
      ],
    },
  ];

  const timeline = [
    ['submitted', 'Submitted', ['pending', 'submitted']],
    ['under_review', 'Under Review', ['under_review']],
    ['pending_docs', 'Pending Documents', ['pending_docs']],
    ['for_interview', 'For Interview', ['for_interview']],
    ['approved', 'Decision', ['approved', 'accepted', 'enrolled', 'rejected', 'cancelled']],
  ];

  function $(id) {
    return document.getElementById(id);
  }

  function escapeHtml(value) {
    return String(value == null ? '' : value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function labelStatus(status) {
    const labels = {
      pending: 'Pending',
      submitted: 'Submitted',
      under_review: 'Under Review',
      pending_docs: 'Pending Documents',
      for_interview: 'For Interview',
      approved: 'Approved',
      accepted: 'Accepted',
      enrolled: 'Enrolled',
      rejected: 'Rejected',
      cancelled: 'Cancelled',
    };
    return labels[status] || String(status || 'Pending');
  }

  function labelInterviewStatus(interview) {
    const raw = String(interview?.status || '').trim().toLowerCase();
    if (['scheduled', 'interview scheduled'].includes(raw)) return 'Scheduled';
    if (raw === 'pending' && (interview?.interview_date || interview?.interview_time)) return 'Scheduled';
    if (raw === 'completed' || raw === 'done') return 'Completed';
    if (raw === 'cancelled' || raw === 'canceled') return 'Cancelled';
    if (raw === 'no show' || raw === 'no-show') return 'No Show';
    return interview?.status || 'Pending';
  }

  async function api(endpoint, options = {}) {
    if (state.token) touchSession();
    const headers = { Accept: 'application/json', ...(options.headers || {}) };
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrf) headers['X-CSRF-TOKEN'] = csrf;
    if (state.token) headers.Authorization = 'Bearer ' + state.token;
    if (options.body && !(options.body instanceof FormData)) headers['Content-Type'] = 'application/json';

    const response = await fetch(apiBase + endpoint, { ...options, headers });
    const text = await response.text();
    let data = {};
    try { data = JSON.parse(text); } catch (_) { data = {}; }
    if (!response.ok) {
      const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
      throw new Error(errors || data.message || 'Request failed.');
    }
    if (state.token) touchSession();
    return data;
  }

  function toast(message, type = 'success') {
    const el = $('portalToast');
    if (!el) return;
    const iconMap = {
      success: 'tick-circle',
      error: 'info-circle',
      warning: 'alert-triangle',
    };
    const icon = iconMap[type] || 'tick-circle';
    el.className = `toast show toast--${type}`;
    el.innerHTML = `
      <i data-iconsax="${icon}"></i>
      <span>${escapeHtml(message)}</span>
    `;
    if (window.iconsax) window.iconsax.createIcons();
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3200);
  }

  function showStep(name) {
    ['lookupStep', 'otpStep', 'dashboardStep'].forEach(id => $(id)?.classList.toggle('active', id === name));
    document.body.classList.toggle('portal-dashboard-open', name === 'dashboardStep');
    document.getElementById('navbar')?.classList.toggle('scrolled', name === 'dashboardStep' || window.scrollY > 60);
    $('portalAccess')?.classList.toggle('hidden', name === 'dashboardStep');
    $('portalHelpBand')?.classList.toggle('hidden', name === 'dashboardStep');
    if (name === 'dashboardStep') window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function setBusy(form, busy) {
    form?.querySelectorAll('button, input, select, textarea').forEach(el => { el.disabled = busy; });
  }

  function setButtonLoading(button, loading) {
    if (!button) return;
    const idleText = button.dataset.idleText || button.textContent;
    const loadingText = button.dataset.loadingText || 'Loading...';
    button.classList.toggle('is-loading', loading);
    button.disabled = loading;
    button.textContent = loading ? loadingText : idleText;
  }

  function updateResendButton() {
    const button = $('resendOtp');
    if (!button) return;
    if (button.classList.contains('is-loading')) return;
    if (state.resendSeconds > 0) {
      button.disabled = true;
      button.textContent = `Resend OTP (${state.resendSeconds}s)`;
      return;
    }
    button.disabled = state.sendingOtp || state.verifyingOtp;
    button.textContent = button.dataset.idleText || 'Resend OTP';
  }

  function updateVerifyButton() {
    const button = $('verifyOtpBtn');
    if (!button || state.verifyingOtp) return;
    const idleText = button.dataset.idleText || 'Verify and Continue';
    button.disabled = !state.otpReady || state.sendingOtp;
    button.textContent = button.disabled ? 'Waiting for OTP...' : idleText;
  }

  function startResendCooldown(seconds = 60) {
    state.resendSeconds = seconds;
    if (state.resendTimer) clearInterval(state.resendTimer);
    updateResendButton();
    state.resendTimer = setInterval(() => {
      state.resendSeconds = Math.max(0, state.resendSeconds - 1);
      updateResendButton();
      if (state.resendSeconds <= 0) {
        clearInterval(state.resendTimer);
        state.resendTimer = null;
      }
    }, 1000);
  }

  function formatDate(value) {
    if (!value) return 'Not set';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
  }

  function formatDateTime(value) {
    if (!value) return 'No edits yet';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleString(undefined, { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
  }

  function availablePrograms(forSecond) {
    const programs = state.payload?.programs || [];
    return programs.filter(p => p.is_active && Number(p.slots_left || 0) > 0 && (!forSecond || !p.has_board_exam));
  }

  function renderSelect(name, value, forSecond) {
    const programs = availablePrograms(forSecond);
    const current = value ? `<option value="${escapeHtml(value)}">${escapeHtml(value)} (current)</option>` : '<option value="">Select program</option>';
    const options = programs
      .filter(p => p.name !== value)
      .map(p => `<option value="${escapeHtml(p.name)}">${escapeHtml(p.name)} - ${Number(p.slots_left || 0)} slots</option>`)
      .join('');
    return `<select class="portal-select" name="${name}" ${state.editing ? '' : 'disabled'}>${current}${options}</select>`;
  }

  function renderControl(key, type, value) {
    if (type === 'textarea') {
      return `<textarea class="portal-textarea" name="${key}" ${state.editing ? '' : 'disabled'}>${escapeHtml(value || '')}</textarea>`;
    }
    if (type === 'select:first') return renderSelect(key, value, false);
    if (type === 'select:second') return renderSelect(key, value, true);
    const step = type === 'number' ? ' step="any"' : '';
    return `<input class="portal-input" type="${type}" name="${key}" value="${escapeHtml(value || '')}" ${step} ${state.editing ? '' : 'disabled'}>`;
  }

  function renderDetails() {
    const app = state.payload.application || {};
    const form = $('detailsForm');
    if (!form) return;
    form.innerHTML = fields.map(group => `
      <div>
        <h4 class="text-base font-bold text-[#0f1e3d] mb-3">${escapeHtml(group.title)}</h4>
        <div class="detail-grid">
          ${group.items.map(([key, label, type]) => `
            <div class="detail-field ${type === 'textarea' ? 'md:col-span-2' : ''}">
              <label>${escapeHtml(label)}</label>
              ${renderControl(key, type, app[key])}
            </div>
          `).join('')}
        </div>
      </div>
    `).join('');

    $('editDetailsBtn')?.classList.toggle('hidden', state.editing || !state.payload.editable);
    $('saveDetailsBtn')?.classList.toggle('hidden', !state.editing);
    $('cancelEditBtn')?.classList.toggle('hidden', !state.editing);
    $('choiceWarning')?.classList.toggle('hidden', !state.editing);
  }

  function renderHeader() {
    const app = state.payload.application || {};
    const name = [app.first_name, app.middle_name, app.last_name, app.suffix].filter(Boolean).join(' ') || 'Applicant';
    $('applicantName').textContent = name;
    $('applicantReference').textContent = app.reference_number || '';
    const badge = $('statusBadge');
    badge.textContent = labelStatus(app.status);
    badge.className = 'status-badge status-' + (app.status || 'pending');
    $('lastUpdated').textContent = 'Last updated: ' + formatDateTime(app.last_edited_at || app.updated_at || app.submitted_at);
    $('lockedNotice')?.classList.toggle('hidden', !!state.payload.editable);
    $('pendingDocsNotice')?.classList.toggle('hidden', app.status !== 'pending_docs');
  }

  function renderTimeline() {
    const app = state.payload.application || {};
    const status = app.status || 'pending';
    const activeIndex = Math.max(0, timeline.findIndex(item => item[2].includes(status)));
    
    const isTerminalStatus = ['approved', 'accepted', 'enrolled', 'rejected', 'cancelled'].includes(status);

    $('statusTimeline').innerHTML = timeline.map((item, index) => {
      const isCurrent = index === activeIndex;
      
      // A step is done if it is before the active index,
      // or if it's the active index and we have reached a terminal stage
      const done = index < activeIndex || (index === activeIndex && isTerminalStatus);
      
      const isTerminalStep = item[0] === 'approved' && ['rejected', 'cancelled'].includes(status);
      const title = isTerminalStep ? labelStatus(status) : item[1];
      
      // Determine what to display inside the timeline dot
      let dotContent = index + 1;
      if (done) {
        if (isTerminalStep) {
          dotContent = '&#10007;'; // High-quality ballot X symbol
        } else {
          dotContent = '&#10003;'; // Checkmark symbol
        }
      }

      // Determine subtext
      let subtext = 'Waiting';
      if (isCurrent) {
        if (status === 'enrolled') {
          subtext = 'Enrolled';
        } else if (status === 'accepted') {
          subtext = 'Accepted';
        } else if (status === 'approved') {
          subtext = 'Approved';
        } else if (status === 'rejected') {
          subtext = 'Closed';
        } else if (status === 'cancelled') {
          subtext = 'Closed';
        } else {
          subtext = 'Current stage';
        }
      } else if (done) {
        subtext = 'Completed';
      }

      const terminalClass = (isTerminalStep && done) ? 'timeline-item--terminal' : '';

      return `
        <div class="timeline-item ${done ? 'done' : ''} ${isCurrent ? 'current' : ''} ${terminalClass}">
          <div class="timeline-dot">${dotContent}</div>
          <div>
            <p class="font-bold text-slate-900">${escapeHtml(title)}</p>
            <p class="text-sm text-slate-500">${subtext}</p>
          </div>
        </div>
      `;
    }).join('');
  }

  function renderDocuments() {
    const editable = !!state.payload.editable;
    $('documentList').innerHTML = (state.payload.documents || []).map(doc => `
      <div class="doc-row ${doc.uploaded ? '' : 'missing'}">
        <div>
          <p class="font-bold text-slate-900">${escapeHtml(doc.label)}</p>
          <p class="text-sm ${doc.uploaded ? 'text-emerald-700' : 'text-amber-700'}">${doc.uploaded ? 'Uploaded' : 'Missing'}</p>
          ${doc.url ? `<a href="${escapeHtml(doc.url)}" target="_blank" class="text-sm font-bold text-blue-700">View file</a>` : ''}
        </div>
        <form class="doc-upload-form flex flex-col sm:flex-row gap-2" data-type="${escapeHtml(doc.type)}">
          <input class="portal-input" type="file" name="file" accept=".jpg,.jpeg,.png,.webp,.pdf" ${editable ? '' : 'disabled'}>
          <button class="portal-btn portal-btn-ghost" type="submit" ${editable ? '' : 'disabled'}>${doc.uploaded ? 'Replace' : 'Upload'}</button>
        </form>
      </div>
    `).join('');
  }

  function renderInterview() {
    const interview = state.payload.interview;
    if (!interview) {
      $('interviewCard').innerHTML = '<div class="portal-alert portal-alert-info">No interview schedule yet.</div>';
      return;
    }
    $('interviewCard').innerHTML = `
      <div class="portal-card p-5 shadow-none">
        <p class="text-sm text-slate-500">Interview Status</p>
        <p class="text-2xl font-bold text-[#0f1e3d]">${escapeHtml(interview.display_status || labelInterviewStatus(interview))}</p>
        <p class="text-sm text-slate-500 mt-1">This status is updated by the admissions office.</p>
        <div class="grid md:grid-cols-3 gap-4 mt-5">
          <div><p class="text-xs font-bold text-slate-500 uppercase">Date</p><p class="font-bold">${escapeHtml(formatDate(interview.interview_date))}</p></div>
          <div><p class="text-xs font-bold text-slate-500 uppercase">Time</p><p class="font-bold">${escapeHtml(interview.interview_time || 'Not set')}</p></div>
          <div><p class="text-xs font-bold text-slate-500 uppercase">Program</p><p class="font-bold">${escapeHtml(interview.program || state.payload.application.first_choice || 'Not set')}</p></div>
        </div>
      </div>
    `;
  }

  function render() {
    if (!state.payload) return;
    renderHeader();
    renderTimeline();
    renderDetails();
    renderDocuments();
    renderInterview();
    showStep('dashboardStep');
    
    if (state.payload.application && state.payload.application.id) {
      initRealtime(state.payload.application.id);
    }
  }

  async function loadPortal() {
    const data = await api('/application-status/data');
    state.payload = data.data;
    render();
  }

  function collectDetails() {
    const data = {};
    new FormData($('detailsForm')).forEach((value, key) => {
      data[key] = value === '' ? null : value;
    });
    return data;
  }

  function bind() {
    const navbar = document.getElementById('navbar');
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    function updateNavbar() {
      if (!navbar) return;
      navbar.classList.toggle('scrolled', document.body.classList.contains('portal-dashboard-open') || window.scrollY > 60);
    }

    function toggleMenu(forceClose = false) {
      const isOpen = forceClose ? false : !mobileMenu?.classList.contains('active');
      menuToggle?.classList.toggle('active', isOpen);
      mobileMenu?.classList.toggle('active', isOpen);
      document.body.classList.toggle('menu-open', isOpen);
      menuToggle?.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      const navCta = document.querySelector('.btn-primary-nav');
      if (navCta) navCta.style.display = (isOpen && window.innerWidth < 768) ? 'none' : '';
    }

    function toggleLogoutModal(open) {
      const modal = $('applicantLogoutModal');
      if (!modal) return;
      modal.classList.toggle('is-open', open);
      modal.setAttribute('aria-hidden', open ? 'false' : 'true');
      if (open) $('cancelApplicantLogout')?.focus();
    }

    updateNavbar();
    window.addEventListener('scroll', updateNavbar, { passive: true });
    menuToggle?.addEventListener('click', e => {
      e.stopPropagation();
      toggleMenu();
    });
    document.querySelectorAll('.mobile-nav-link, .mobile-btn-primary').forEach(link => {
      link.addEventListener('click', () => toggleMenu(true));
    });

    $('applicantLogoutBtn')?.addEventListener('click', () => {
      toggleLogoutModal(true);
    });
    $('cancelApplicantLogout')?.addEventListener('click', () => {
      toggleLogoutModal(false);
    });
    $('confirmApplicantLogout')?.addEventListener('click', () => {
      toggleLogoutModal(false);
      resetPortalSession('You have been logged out of the applicant portal.');
    });
    $('applicantLogoutModal')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) toggleLogoutModal(false);
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') toggleLogoutModal(false);
    });

    ['click', 'keydown', 'mousemove', 'touchstart', 'scroll'].forEach(eventName => {
      window.addEventListener(eventName, touchSession, { passive: true });
    });

    state.idleTimer = setInterval(() => {
      if (!state.token) return;
      const lastActivity = Number(sessionStorage.getItem(ACTIVITY_KEY) || 0);
      if (!lastActivity || now() - lastActivity > IDLE_LIMIT_MS) {
        resetPortalSession('Your applicant portal session expired due to inactivity.');
      }
    }, 60000);

    $('lookupForm')?.addEventListener('submit', async e => {
      e.preventDefault();
      if (state.sendingOtp) return;
      const form = e.currentTarget;
      const button = $('sendOtpBtn');
      const fd = new FormData(form);
      state.ref = String(fd.get('reference_number') || '').trim();
      state.email = String(fd.get('email') || '').trim();
      state.sendingOtp = true;
      state.otpReady = false;
      showStep('otpStep');
      setBusy(form, true);
      setButtonLoading(button, true);
      updateResendButton();
      updateVerifyButton();
      try {
        await api('/application-status/request-otp', {
          method: 'POST',
          body: JSON.stringify({ reference_number: state.ref, email: state.email }),
        });
        state.otpReady = true;
        toast('Verification code sent if the details match.');
        startResendCooldown(60);
      } catch (err) {
        state.otpReady = false;
        showStep('lookupStep');
        toast(err.message, 'error');
      } finally {
        state.sendingOtp = false;
        setBusy(form, false);
        setButtonLoading(button, false);
        updateResendButton();
        updateVerifyButton();
      }
    });

    $('otpForm')?.addEventListener('submit', async e => {
      e.preventDefault();
      if (state.verifyingOtp) return;
      const form = e.currentTarget;
      const button = $('verifyOtpBtn');
      const otp = String(new FormData(form).get('otp') || '').replace(/\D/g, '').slice(0, 6);
      state.verifyingOtp = true;
      setBusy(form, true);
      setButtonLoading(button, true);
      updateResendButton();
      $('backToLookup')?.setAttribute('disabled', 'disabled');
      try {
        const data = await api('/application-status/verify', {
          method: 'POST',
          body: JSON.stringify({ reference_number: state.ref, email: state.email, otp }),
        });
        state.token = data.portal_token;
        rememberSession(state.token);
        state.payload = data.data;
        render();
        updateNavbar();
        toast('Applicant portal opened.');
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        state.verifyingOtp = false;
        setBusy(form, false);
        setButtonLoading(button, false);
        $('backToLookup')?.removeAttribute('disabled');
        updateResendButton();
      }
    });

    $('backToLookup')?.addEventListener('click', () => {
      state.otpReady = false;
      showStep('lookupStep');
    });
    $('resendOtp')?.addEventListener('click', async () => {
      if (state.sendingOtp || state.verifyingOtp || state.resendSeconds > 0) return;
      const button = $('resendOtp');
      state.sendingOtp = true;
      state.otpReady = false;
      setButtonLoading(button, true);
      updateResendButton();
      updateVerifyButton();
      try {
        await api('/application-status/request-otp', {
          method: 'POST',
          body: JSON.stringify({ reference_number: state.ref, email: state.email }),
        });
        state.otpReady = true;
        toast('A new verification code was sent if the details match.');
        startResendCooldown(60);
      } catch (err) {
        state.otpReady = false;
        toast(err.message, 'error');
      } finally {
        state.sendingOtp = false;
        setButtonLoading(button, false);
        updateResendButton();
        updateVerifyButton();
      }
    });

    $('portalTabs')?.addEventListener('click', e => {
      const tab = e.target.closest('.portal-tab');
      if (!tab) return;
      document.querySelectorAll('.portal-tab').forEach(el => el.classList.toggle('active', el === tab));
      document.querySelectorAll('.portal-panel').forEach(el => el.classList.toggle('active', el.id === 'panel-' + tab.dataset.tab));
    });

    $('editDetailsBtn')?.addEventListener('click', () => {
      if (!state.payload?.editable) return;
      state.editing = true;
      renderDetails();
    });

    $('cancelEditBtn')?.addEventListener('click', () => {
      state.editing = false;
      renderDetails();
    });

    $('saveDetailsBtn')?.addEventListener('click', async () => {
      if (!state.payload?.editable) return;
      const button = $('saveDetailsBtn');
      const payload = collectDetails();
      setButtonLoading(button, true);
      try {
        const data = await api('/application-status/data', {
          method: 'PATCH',
          body: JSON.stringify(payload),
        });
        state.payload = data.data;
        state.editing = false;
        render();
        toast('Application updated.');
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        setButtonLoading(button, false);
      }
    });

    $('documentList')?.addEventListener('submit', async e => {
      const form = e.target.closest('.doc-upload-form');
      if (!form) return;
      e.preventDefault();
      const file = form.querySelector('[name="file"]')?.files?.[0];
      if (!file) {
        toast('Please choose a file first.', 'warning');
        return;
      }
      const fd = new FormData();
      fd.append('document_type', form.dataset.type);
      fd.append('file', file);
      setBusy(form, true);
      try {
        const data = await api('/application-status/documents', { method: 'POST', body: fd });
        state.payload = data.data;
        render();
        toast('Document uploaded.');
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        setBusy(form, false);
      }
    });

    document.addEventListener('change', e => {
      if (!e.target.matches('[name="first_choice"]')) return;
      const first = e.target.value;
      const second = document.querySelector('[name="second_choice"]');
      if (second && second.value === first) second.value = '';
    });
  }

  document.addEventListener('DOMContentLoaded', async () => {
    bind();
    state.token = readStoredToken();
    if (!state.token) {
      showStep('lookupStep');
      return;
    }

    try {
      await loadPortal();
    } catch (_) {
      resetPortalSession();
    }
  });

  let realtimeChannel = null;
  function initRealtime(applicationId) {
    if (realtimeChannel) return;
    const url = window.SUPABASE_URL;
    const anonKey = window.SUPABASE_ANON_KEY;
    if (url && anonKey && typeof supabase !== 'undefined' && applicationId) {
      try {
        const client = supabase.createClient(url, anonKey);
        realtimeChannel = client.channel('applicant-realtime')
          .on('postgres_changes', { 
            event: 'UPDATE', 
            schema: 'public', 
            table: 'applications', 
            filter: `id=eq.${applicationId}` 
          }, (payload) => {
            console.log('Realtime update to current application received:', payload);
            loadPortal();
          })
          .subscribe((status) => {
            console.log('Applicant Realtime subscription status:', status);
            if (status !== 'SUBSCRIBED') {
              startFallbackPolling();
            }
          });
      } catch (err) {
        console.warn('Failed to initialize Supabase Realtime for applicant:', err);
        startFallbackPolling();
      }
    } else {
      console.log('Supabase Realtime not configured for applicant portal, starting fallback polling.');
      startFallbackPolling();
    }
  }

  let pollingInterval = null;
  function startFallbackPolling() {
    if (pollingInterval) return;
    console.log('Realtime fallback: Polling active (30s).');
    pollingInterval = setInterval(() => {
      loadPortal();
    }, 30000);
  }
})();
