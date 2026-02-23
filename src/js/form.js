/* ═══════════════════════════════════════════════════════════
   BTECH ONLINE ADMISSION — form.js
═══════════════════════════════════════════════════════════ */

'use strict';

let currentStep = 1;
const TOTAL_STEPS = 12;

const STEP_LABELS = [
  'Respondent', 'Personal',   'Academic',
  'Contact',    'Perm. Addr', 'Pres. Addr',
  'Courses',    'GWA',        'Eligibility',
  'Declaration','Documents',  'Review'
];

const STEP_HINTS = [
  'Select your applicant type',
  'Fill in personal details',
  'Enter academic information',
  'Enter parent contact numbers',
  'Enter your permanent address',
  'Enter your present address',
  'Choose your preferred courses',
  'Enter your GWA grades',
  'Answer eligibility questions',
  'Review and accept declarations',
  'Upload your photo & required documents',
  'Review all details then submit'
];

const STEP_PANELS = {
  1:  [0],   // Respondent
  2:  [1],   // Personal
  3:  [2],   // Academic
  4:  [3],   // Contact Info
  5:  [4],   // Permanent Address
  6:  [5],   // Present Address
  7:  [6],   // Courses
  8:  [7],   // GWA
  9:  [8],   // Eligibility
  10: [9],   // Declaration
  11: [10],  // Upload
  12: [11],  // Review
};

let uploadedPic  = null;
let uploadedDocs = [];
let generatedRef = '';

const DOCS_MAP = {
  'Freshmen':     ['Original Report Card (Grade 11 & 12)', 'Original Diploma', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'Transferee':   ['Official Transcript of Records', 'Certificate of Honorable Dismissal', 'Diploma / Cert. of Completed Courses', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'ALS Graduate': ['ALS A&E Certificate', 'ALS Test Results / Transcript', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'Returnee':     ['BTECH Official Transcript of Records', 'Certificate of Candidacy (if applicable)', 'Written Statement of Purpose', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character', 'Academic Clearance (prev. semester)']
};

const $  = sel => document.querySelector(sel);
const $$ = sel => document.querySelectorAll(sel);
const gv = name => { const el = $(`[name="${name}"]`); return el ? el.value : ''; };
const gr = name => { const el = $(`input[name="${name}"]:checked`); return el ? el.value : ''; };
const se = (id, msg)  => { const el = document.getElementById(id); if (el) el.textContent = msg; };
const si = (id, html) => { const el = document.getElementById(id); if (el) el.innerHTML = html; };
const markErr    = (name, on) => { const el = $(`[name="${name}"]`); if (el) el.classList.toggle('err', on); };
const showBanner = (id, on)   => { const el = document.getElementById(id); if (el) el.classList.toggle('show', on); };

function buildStepper() {
  const row = document.getElementById('stepperRow');
  if (!row) return;
  row.innerHTML = '';
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'step-btn' +
      (i === currentStep ? ' active' : '') +
      (i < currentStep  ? ' done'   : '');
    btn.innerHTML = `<span class="snum">${i < currentStep ? '✓' : i}</span><span class="slbl">${STEP_LABELS[i - 1]}</span>`;
    btn.onclick = () => { if (i <= currentStep) { currentStep = i; show(); } };
    row.appendChild(btn);
    if (i < TOTAL_STEPS) {
      const conn = document.createElement('div');
      conn.className = 'step-conn' + (i < currentStep ? ' done' : '');
      row.appendChild(conn);
    }
  }
}

function show() {
  const panels = $$('.sc');
  panels.forEach(el => el.classList.remove('active'));
  const indices = STEP_PANELS[currentStep] || [currentStep - 1];
  indices.forEach(idx => { if (panels[idx]) panels[idx].classList.add('active'); });

  se('stepLbl', currentStep);
  const hintEl = document.getElementById('stepHint');
  if (hintEl) hintEl.textContent = STEP_HINTS[currentStep - 1] || '';

  const pct = Math.round((currentStep / TOTAL_STEPS) * 100);
  const bar = document.getElementById('pbar');
  if (bar) bar.style.width = pct + '%';

  const deg  = (pct / 100) * 360;
  const ring = document.getElementById('ring');
  if (ring) ring.style.background = `conic-gradient(#40916c ${deg}deg, rgba(255,255,255,.18) ${deg}deg)`;
  se('pctTxt', pct + '%');

  const prevBtn = document.getElementById('prevBtn');
  if (prevBtn) prevBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';

  const navBar  = document.getElementById('navBar');
  const nextBtn = document.getElementById('nextBtn');
  if (currentStep === TOTAL_STEPS) {
    if (navBar) navBar.style.display = 'none';
  } else {
    if (navBar) navBar.style.display = 'flex';
    if (nextBtn) {
      nextBtn.innerHTML = `Next <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>`;
      nextBtn.className = 'bp';
      nextBtn.onclick = () => nav(1);
    }
  }

  buildStepper();
  updateAcadBlocks();
  if (currentStep === 11) buildDocSlots();
  if (currentStep === 12) buildReview();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nav(dir) {
  if (dir === 1 && !validate(currentStep)) return;
  if (dir === 1) showToast();
  currentStep = Math.min(Math.max(currentStep + dir, 1), TOTAL_STEPS);
  show();
}

function validate(step) {
  let ok = true;

  if (step === 1) {
    const v = !!gr('respondentType');
    showBanner('e1', !v);
    return v;
  }

  if (step === 2) {
    const required = [
      ['surname','e-sur'],['firstName','e-fn'],['placeOfBirth','e-pob'],
      ['motherFirstName','e-mfn'],['motherMaidenName','e-mln'],
      ['fatherSurname','e-fls'],['fatherFirstName','e-ffn']
    ];
    required.forEach(([field, errId]) => {
      const valid = gv(field).trim().length > 0;
      markErr(field, !valid);
      se(errId, valid ? '' : 'This field is required');
      if (!valid) ok = false;
    });
    const hasSex = !!gr('sex');
    se('e-sex', hasSex ? '' : 'Please select sex');
    if (!hasSex) ok = false;
    const dob = gv('dateOfBirth');
    if (!dob) { se('e-dob', 'Date of birth is required'); ok = false; }
    else {
      const age = Math.floor((Date.now() - new Date(dob + 'T00:00:00')) / (365.25 * 24 * 3600 * 1000));
      if (age < 15 || age > 65) { se('e-dob', 'Please enter a valid birth date'); ok = false; }
      else se('e-dob', '');
    }
    showBanner('e2', !ok);
    return ok;
  }

  if (step === 3) {
    ['acadElem','acadHS','acadSHS','acadTertiary'].forEach(id => {
      const block = document.getElementById(id);
      if (!block || block.style.display === 'none') return;
      block.querySelectorAll('input.fi').forEach(input => {
        const valid = input.value.trim().length > 0;
        input.classList.toggle('err', !valid);
        if (!valid) ok = false;
      });
    });
    return ok;
  }

  if (step === 4) {
    const re = /^9\d{9}$/;
    const v1 = re.test(gv('contactNumber'));
    const v2 = re.test(gv('alternateContactNumber'));
    se('e-c1', v1 ? '' : 'Valid 10-digit number starting with 9');
    se('e-c2', v2 ? '' : 'Valid 10-digit number starting with 9');
    markErr('contactNumber', !v1);
    markErr('alternateContactNumber', !v2);
    ok = v1 && v2;
    showBanner('e3', !ok);
    return ok;
  }

  if (step === 5) {
    [['permanentProvince','e-pp'],['permanentCity','e-pc'],['permanentBarangay','e-pb'],['permanentZipCode','e-pz']].forEach(([field, errId]) => {
      const valid = gv(field).trim().length > 0;
      markErr(field, !valid);
      se(errId, valid ? '' : 'Required');
      if (!valid) ok = false;
    });
    const zip = gv('permanentZipCode');
    if (zip && !/^\d{4}$/.test(zip)) { se('e-pz', 'ZIP must be 4 digits'); markErr('permanentZipCode', true); ok = false; }
    showBanner('e4', !ok);
    return ok;
  }

  if (step === 6) {
    [['presentProvince','e-rp'],['presentCity','e-rc'],['presentBarangay','e-rb'],['presentZipCode','e-rz']].forEach(([field, errId]) => {
      const valid = gv(field).trim().length > 0;
      markErr(field, !valid);
      se(errId, valid ? '' : 'Required');
      if (!valid) ok = false;
    });
    const zip = gv('presentZipCode');
    if (zip && !/^\d{4}$/.test(zip)) { se('e-rz', 'ZIP must be 4 digits'); markErr('presentZipCode', true); ok = false; }
    showBanner('e5', !ok);
    return ok;
  }

  if (step === 7) {
    const f1 = gr('firstChoice');
    const f2 = gr('secondChoice');
    if (!f1) { se('e-fc', 'Select 1st choice'); ok = false; } else se('e-fc', '');
    if (!f2) { se('e-sc2', 'Select 2nd choice'); ok = false; }
    else if (f1 && f1 === f2) { se('e-sc2', 'Must differ from 1st choice'); se('e6t', '1st and 2nd choices must be different.'); ok = false; }
    else se('e-sc2', '');
    showBanner('e6', !ok);
    return ok;
  }

  if (step === 8) {
    const g1 = parseFloat(gv('grade11GWA'));
    const g2 = parseFloat(gv('grade12GWA'));
    const v1 = !isNaN(g1) && g1 >= 70 && g1 <= 100;
    const v2 = !isNaN(g2) && g2 >= 70 && g2 <= 100;
    se('e-g1', v1 ? '' : 'Enter a value between 70–100');
    se('e-g2', v2 ? '' : 'Enter a value between 70–100');
    markErr('grade11GWA', !v1);
    markErr('grade12GWA', !v2);
    ok = v1 && v2;
    if (ok) updateGWADisplay(g1, g2);
    showBanner('e7', !ok);
    return ok;
  }

  if (step === 9) {
    [['differentlyAbled','e-pwd'],['soloParent','e-solo'],['fourPs','e-4ps']].forEach(([name, errId]) => {
      const valid = !!gr(name);
      se(errId, valid ? '' : 'Please select Yes or No');
      if (!valid) ok = false;
    });
    showBanner('e8', !ok);
    return ok;
  }

  if (step === 10) {
    const all = ['declarationTruth','dataPrivacy','authorizedProcessing']
      .every(n => { const el = $(`input[name="${n}"]`); return el && el.checked; });
    showBanner('e9', !all);
    return all;
  }

  if (step === 11) {
    const hasPic  = !!uploadedPic;
    const hasDocs = uploadedDocs.length > 0;
    se('e-pic', hasPic ? '' : 'Please upload your 2×2 ID picture');
    if (!hasDocs) se('e10t', 'Please upload at least one required document.');
    ok = hasPic && hasDocs;
    showBanner('e10', !ok);
    return ok;
  }

  return true;
}

function updateGWADisplay(g1, g2) {
  const avg = ((g1 + g2) / 2).toFixed(2);
  const remark = avg >= 90 ? '⭐ Outstanding' : avg >= 85 ? '✅ Very Satisfactory' : avg >= 80 ? '👍 Satisfactory' : '📘 Passing';
  se('avgGWA', avg);
  se('gwaRmk', remark);
  const el = document.getElementById('gwaDsp');
  if (el) el.style.display = 'block';
}

function updateAcadBlocks() {
  const type      = gr('respondentType');
  const shs       = document.getElementById('acadSHS');
  const tertiary  = document.getElementById('acadTertiary');
  const als       = document.getElementById('acadALS');
  const returnee  = document.getElementById('acadReturnee');
  // Hide all by default
  if (shs)      shs.style.display      = 'block';
  if (tertiary) tertiary.style.display = 'none';
  if (als)      als.style.display      = 'none';
  if (returnee) returnee.style.display = 'none';

  if (type === 'Freshmen') {
    if (shs)      shs.style.display      = 'block';
    if (tertiary) tertiary.style.display = 'none';
    if (als)      als.style.display      = 'none';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'Transferee') {
    if (shs)      shs.style.display      = 'block';
    if (tertiary) tertiary.style.display = 'block';
    if (als)      als.style.display      = 'none';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'ALS Graduate') {
    if (shs)      shs.style.display      = 'none';
    if (tertiary) tertiary.style.display = 'none';
    if (als)      als.style.display      = 'block';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'Returnee') {
    if (shs)      shs.style.display      = 'block';
    if (tertiary) tertiary.style.display = 'none';
    if (als)      als.style.display      = 'none';
    if (returnee) returnee.style.display = 'block';
  }
}

function initSameAsPermanent() {
  const chk = document.getElementById('sameChk');
  if (!chk) return;
  chk.addEventListener('change', function () {
    ['Province','City','Barangay','ZipCode'].forEach(f => {
      const el = $(`[name="present${f}"]`);
      if (!el) return;
      el.value    = this.checked ? gv('permanent' + f) : '';
      el.disabled = this.checked;
    });
  });
}

function initGWALive() {
  ['grade11GWA','grade12GWA'].forEach(name => {
    const el = $(`[name="${name}"]`);
    if (!el) return;
    el.addEventListener('input', () => {
      const g1 = parseFloat(gv('grade11GWA'));
      const g2 = parseFloat(gv('grade12GWA'));
      const display = document.getElementById('gwaDsp');
      if (!isNaN(g1) && !isNaN(g2) && g1 >= 70 && g1 <= 100 && g2 >= 70 && g2 <= 100) updateGWADisplay(g1, g2);
      else if (display) display.style.display = 'none';
    });
  });
}

function initCourseConflict() {
  $$('input[name="firstChoice"], input[name="secondChoice"]').forEach(radio => {
    radio.addEventListener('change', () => {
      const f1 = gr('firstChoice');
      const f2 = gr('secondChoice');
      se('e-sc2', (f1 && f2 && f1 === f2) ? 'Must differ from 1st choice' : '');
    });
  });
}

function initDigitOnly() {
  $$('input[type="tel"]').forEach(el => {
    el.addEventListener('input', () => { el.value = el.value.replace(/\D/g, ''); });
  });
  $$('[name="permanentZipCode"], [name="presentZipCode"]').forEach(el => {
    el.addEventListener('input', () => { el.value = el.value.replace(/\D/g, ''); });
  });
}

function handlePic(input) {
  const file = input.files[0];
  if (!file) return;
  if (!file.type.match(/image\/(jpeg|png|jpg)/)) { se('e-pic', 'Only JPG/PNG files accepted'); return; }
  if (file.size > 5 * 1024 * 1024) { se('e-pic', 'File must be under 5MB'); return; }
  const reader = new FileReader();
  reader.onload = e => {
    uploadedPic = { name: file.name, dataURL: e.target.result };
    const zone = document.getElementById('picZone');
    if (!zone) return;
    zone.classList.add('uploaded');
    zone.innerHTML = `<input type="file" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%"><img src="${e.target.result}" alt="2x2 photo" style="width:100%;height:100%;object-fit:cover;border-radius:10px">`;
    se('e-pic', '');
    showBanner('e10', false);
  };
  reader.readAsDataURL(file);
}

function buildDocSlots() {
  const container = document.getElementById('docSlots');
  if (!container) return;
  container.innerHTML = '';
  uploadedDocs.forEach((doc, i) => {
    const div = document.createElement('div');
    div.className = 'doc-item ok';
    div.innerHTML = `<span class="doc-icon">📄</span><div style="flex:1;min-width:0"><p style="font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${doc.name}</p><p style="font-size:11px;color:#9aa5b1;margin-top:2px">${(doc.size / 1024).toFixed(1)} KB</p></div><button class="doc-remove" onclick="removeDoc(${i})">✕ Remove</button>`;
    container.appendChild(div);
  });
}

function handleDocs(input) {
  Array.from(input.files).forEach(file => {
    if (file.size > 20 * 1024 * 1024) { alert(`${file.name} is too large (max 20MB)`); return; }
    uploadedDocs.push({ name: file.name, size: file.size, type: file.type });
  });
  buildDocSlots();
  if (input.value !== undefined) input.value = '';
  if (uploadedDocs.length > 0) showBanner('e10', false);
}

function removeDoc(index) {
  uploadedDocs.splice(index, 1);
  buildDocSlots();
}

function initDragDrop() {
  const zone = document.getElementById('addDocZone');
  if (!zone) return;
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag'); });
  zone.addEventListener('dragleave', ()  => zone.classList.remove('drag'));
  zone.addEventListener('drop', e => { e.preventDefault(); zone.classList.remove('drag'); handleDocs({ files: e.dataTransfer.files }); });
}

function buildReview() {
  if (!generatedRef) {
    generatedRef = 'BTECH-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
  }
  const today = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
  se('rv-ref',  generatedRef);
  se('rv-ref2', generatedRef);
  se('rv-date', today);

  const picBox = document.getElementById('rv-picBox');
  if (picBox) {
    picBox.innerHTML = uploadedPic
      ? `<img src="${uploadedPic.dataURL}" alt="2x2 photo" style="width:100%;height:100%;object-fit:cover">`
      : `<span class="rs-pic-placeholder">2x2<br>ID Photo</span><div class="rs-pic-label">APPLICANT PHOTO</div>`;
  }

  se('rv-surname',    gv('surname').toUpperCase() || '—');
  se('rv-firstName',  gv('firstName').toUpperCase() || '—');
  se('rv-middleName', gv('middleName').toUpperCase() || '—');
  se('rv-suffix',     gv('suffix') || '—');

  const fFull = [gv('fatherFirstName'), gv('fatherMiddleName'), gv('fatherSurname')].filter(Boolean).join(' ').toUpperCase();
  se('rv-fatherName', fFull || '—');

  const rawDob = gv('dateOfBirth');
  if (rawDob) {
    const d = new Date(rawDob + 'T00:00:00');
    se('rv-dob', d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }));
  } else se('rv-dob', '—');
  se('rv-pob', gv('placeOfBirth') || '—');

  const sex = gr('sex');
  se('rv-male',   sex === 'Male'   ? '☑ Male'   : '☐ Male');
  se('rv-female', sex === 'Female' ? '☑ Female' : '☐ Female');

  const buildAddr = prefix => [
    gv(`${prefix}Barangay`) ? `Brgy. ${gv(`${prefix}Barangay`)}` : '',
    gv(`${prefix}City`), gv(`${prefix}Province`), gv(`${prefix}ZipCode`)
  ].filter(Boolean).join(', ') || '—';

  se('rv-permAddr', buildAddr('permanent'));
  se('rv-presAddr', buildAddr('present'));
  se('rv-fContact', gv('contactNumber')          ? '+63 ' + gv('contactNumber')          : '—');
  se('rv-mContact', gv('alternateContactNumber') ? '+63 ' + gv('alternateContactNumber') : '—');

  const mFull = [gv('motherFirstName'), gv('motherMiddleName'), gv('motherMaidenName')].filter(Boolean).join(' ').toUpperCase();
  se('rv-motherName', mFull || '—');

  se('rv-course1', gr('firstChoice')  || '—');
  se('rv-course2', gr('secondChoice') || '—');
  const g1 = parseFloat(gv('grade11GWA'));
  const g2 = parseFloat(gv('grade12GWA'));
  se('rv-g11', isNaN(g1) ? '—' : g1.toFixed(2));
  se('rv-g12', isNaN(g2) ? '—' : g2.toFixed(2));
  if (!isNaN(g1) && !isNaN(g2)) {
    const avg = ((g1 + g2) / 2).toFixed(2);
    se('rv-gwa', avg);
    se('rv-gwarmk', avg >= 90 ? 'Outstanding' : avg >= 85 ? 'Very Satisfactory' : avg >= 80 ? 'Satisfactory' : 'Passing');
  }

  se('rv-type', gr('respondentType') || '—');
  const badge = (val, yesLabel, noLabel) =>
    `<span class="rs-badge${val === 'yes' ? '' : ' no'}">${val === 'yes' ? yesLabel : noLabel}</span>`;
  si('rv-pwd',  badge(gr('differentlyAbled'), 'Yes – PWD', 'No'));
  si('rv-solo', badge(gr('soloParent'),        'Yes',       'No'));
  si('rv-4ps',  badge(gr('fourPs'),            'Yes – 4Ps', 'No'));

  const docsDiv = document.getElementById('rv-docs');
  if (docsDiv) {
    const allDocs = [
      ...(uploadedPic ? [{ name: `2×2 ID Picture (${uploadedPic.name})` }] : []),
      ...uploadedDocs
    ];
    docsDiv.innerHTML = allDocs.length === 0
      ? '<div style="padding:10px 14px;font-size:11px;color:#9aa5b1;grid-column:1/-1">No documents uploaded</div>'
      : allDocs.map(doc => `<div class="rs-doc-item"><span class="doc-check">✓</span><span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${doc.name}</span><span class="rs-badge up">Uploaded</span></div>`).join('');
  }
}

function trySubmit()  { openModal('moConfirm'); }

function doSubmit() {
  closeModal('moConfirm');
  const ref = generatedRef || ('BTECH-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000));
  generatedRef = ref;
  se('refNum', ref);
  setTimeout(() => openModal('moSuccess'), 320);
}

function resetForm() {
  const form = document.getElementById('aForm');
  if (form) form.reset();
  uploadedPic  = null;
  uploadedDocs = [];
  generatedRef = '';
  currentStep  = 1;
  const zone = document.getElementById('picZone');
  if (zone) {
    zone.classList.remove('uploaded');
    zone.innerHTML = `<input type="file" id="picInput" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)"><svg width="30" height="30" fill="none" stroke="#b5cce4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span style="font-size:11px;color:#b5cce4;font-weight:700;margin-top:6px">Click to upload</span>`;
  }
  show();
}

function openModal(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.offsetHeight;
  el.classList.add('show');
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.remove('show');
}

function initModalClose() {
  $$('.mo').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
      if (e.target === this) closeModal(this.id);
    });
  });
}

function showToast() {
  const toast = document.getElementById('saveToast');
  if (!toast) return;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2200);
}

function showReqModal(type) {
  const reqs  = DOCS_MAP[type] || [];
  const modal = document.getElementById('moReq');
  const list  = document.getElementById('moReqList');
  const title = document.getElementById('moReqTitle');
  if (!modal || !list || !title) return;
  title.textContent = `${type} — Required Documents`;
  list.innerHTML = reqs.length ? reqs.map(r => `<li>${r}</li>`).join('') : '<li>No requirements listed.</li>';
  openModal('moReq');
}

function initReqModal() {
  const types = ['Freshmen', 'Transferee', 'ALS Graduate', 'Returnee'];
  types.forEach(type => {
    const input = $(`input[name='respondentType'][value='${type}']`);
    if (!input) return;
    const card = input.closest('.oc');
    if (!card) return;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = 'View docs';
    btn.style.cssText = 'margin-left:auto;padding:3px 10px;font-size:10px;font-weight:700;background:#eef3fa;border:1.5px solid #c3dafe;border-radius:999px;color:var(--navy-mid);cursor:pointer;white-space:nowrap;flex-shrink:0;transition:background .2s;font-family:inherit';
    btn.addEventListener('mouseenter', () => { btn.style.background = '#dbeafe'; });
    btn.addEventListener('mouseleave', () => { btn.style.background = '#eef3fa'; });
    btn.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); showReqModal(type); });
    card.appendChild(btn);
  });
}

function init() {
  initSameAsPermanent();
  initGWALive();
  initCourseConflict();
  initDigitOnly();
  initDragDrop();
  initModalClose();
  initReqModal();
  $$('input[name="respondentType"]').forEach(r => r.addEventListener('change', updateAcadBlocks));
  show();
}

document.addEventListener('DOMContentLoaded', init);