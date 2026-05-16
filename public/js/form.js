/* ═══════════════════════════════════════════════════════════
   BTECH ONLINE ADMISSION - form.js
═══════════════════════════════════════════════════════════ */

'use strict';

let currentStep = 1;
const TOTAL_STEPS = 12;

const STEP_LABELS = [
  'Respondent', 'Personal', 'Academic',
  'Contact', 'Perm. Addr', 'Pres. Addr',
  'Courses', 'GWA', 'Eligibility',
  'Declaration', 'Photo', 'Review'
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
  'Upload your ID photo',
  'Review all details then submit'
];

let isSubmittingGlobal = false;

const STEP_PANELS = {
  1: [0],
  2: [1],
  3: [2],
  4: [3],
  5: [4],
  6: [5],
  7: [6],
  8: [7],
  9: [8],
  10: [9],
  11: [10],
  12: [11],
};

const ZIP_BY_CITY = {
  'Angat': '3012', 'Balagtas': '3016', 'Baliwag': '3006', 'Bocaue': '3018',
  'Bulakan': '3017', 'Bustos': '3007', 'Calumpit': '3003',
  'Doña Remedios Trinidad': '3009', 'Guiguinto': '3015', 'Hagonoy': '3002',
  'Malolos': '3000', 'Marilao': '3019', 'Meycauayan': '3020',
  'Norzagaray': '3013', 'Obando': '3021', 'Pandi': '3014',
  'Paombong': '3001', 'Plaridel': '3004', 'Pulilan': '3005',
  'San Ildefonso': '3010', 'San Jose del Monte': '3023', 'San Miguel': '3011',
  'San Rafael': '3008', 'Santa Maria': '3022',
  'Caloocan': '1400', 'Las Piñas': '1740', 'Makati': '1200',
  'Malabon': '1470', 'Mandaluyong': '1550', 'Manila': '1000',
  'Marikina': '1800', 'Muntinlupa': '1770', 'Navotas': '1485',
  'Parañaque': '1700', 'Pasay': '1300', 'Pasig': '1600',
  'Pateros': '1620', 'Quezon City': '1100', 'San Juan': '1500',
  'Taguig': '1630', 'Valenzuela': '1440',
  'Angono': '1930', 'Antipolo': '1870', 'Baras': '1970',
  'Binangonan': '1940', 'Cainta': '1900', 'Cardona': '1950',
  'Jala-jala': '1990', 'Morong': '1960', 'Pililla': '1910',
  'Rodriguez': '1860', 'San Mateo': '1850', 'Tanay': '1980',
  'Taytay': '1920', 'Teresa': '1880',
  'Alfonso': '4123', 'Amadeo': '4119', 'Bacoor': '4102',
  'Carmona': '4116', 'Cavite City': '4100', 'Dasmariñas': '4114',
  'General Emilio Aguinaldo': '4124', 'General Mariano Alvarez': '4117',
  'General Trias': '4107', 'Imus': '4103', 'Indang': '4122',
  'Kawit': '4104', 'Magallanes': '4113', 'Maragondon': '4112',
  'Mendez': '4121', 'Naic': '4110', 'Noveleta': '4105',
  'Rosario': '4106', 'Silang': '4118', 'Tagaytay': '4120',
  'Tanza': '4108', 'Ternate': '4111', 'Trece Martires': '4109',
  'Alaminos': '4001', 'Bay': '4033', 'Biñan': '4024', 'Cabuyao': '4025',
  'Calamba': '4027', 'Calauan': '4012', 'Cavinti': '4013', 'Famy': '4021',
  'Kalayaan': '4015', 'Liliw': '4004', 'Los Baños': '4030',
  'Luisiana': '4032', 'Lumban': '4014', 'Mabitac': '4020',
  'Magdalena': '4007', 'Majayjay': '4005', 'Nagcarlan': '4002',
  'Paete': '4016', 'Pagsanjan': '4008', 'Pakil': '4017', 'Pangil': '4018',
  'Pila': '4010', 'Rizal': '4003', 'San Pablo': '4000', 'San Pedro': '4023',
  'Santa Cruz': '4009', 'Santa Rosa': '4026', 'Siniloan': '4019',
  'Victoria': '4011',
  'Angeles': '2009', 'Apalit': '2016', 'Arayat': '2012', 'Bacolor': '2001',
  'Candaba': '2013', 'Floridablanca': '2006', 'Guagua': '2003',
  'Lubao': '2005', 'Mabalacat': '2010', 'Macabebe': '2018',
  'Magalang': '2011', 'Masantol': '2017', 'Mexico': '2021',
  'Minalin': '2019', 'Porac': '2008', 'San Fernando': '2000',
  'San Luis': '2014', 'San Simon': '2015', 'Santa Ana': '2022',
  'Santa Rita': '2002', 'Santo Tomas': '2020', 'Sasmuan': '2004',
  'Abucay': '2114', 'Bagac': '2107', 'Balanga': '2100',
  'Dinalupihan': '2110', 'Hermosa': '2111', 'Limay': '2103',
  'Mariveles': '2105', 'Orani': '2112', 'Orion': '2102',
  'Pilar': '2101', 'Samal': '2113',
  'Aliaga': '3111', 'Bongabon': '3128', 'Cabanatuan': '3100',
  'Cabiao': '3107', 'Carranglan': '3123', 'Cuyapo': '3117',
  'Gabaldon': '3131', 'Gapan': '3105', 'General Mamerto Natividad': '3125',
  'General Tinio': '3124', 'Guimba': '3115', 'Jaen': '3109',
  'Laur': '3129', 'Licab': '3112', 'Llanera': '3126', 'Lupao': '3122',
  'Muñoz': '3119', 'Nampicuan': '3116', 'Palayan': '3132',
  'Pantabangan': '3127', 'Peñaranda': '3104', 'Quezon': '3113',
  'San Antonio': '3108', 'San Isidro': '3106', 'San Jose': '3121',
  'San Leonardo': '3102', 'Santa Rosa': '3101', 'Santo Domingo': '3133',
  'Talavera': '3114', 'Talugtug': '3118', 'Zaragoza': '3110'
};

let uploadedPic = null;
let uploadedDocs = [];
let generatedRef = '';
const FORM_PROGRESS_KEY = 'btech_admission_form_progress_v1';
let isRestoringProgress = false;

function debounceProgress(fn, ms = 250) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), ms);
  };
}

function collectProgressData() {
  const form = document.getElementById('aForm');
  if (!form) return null;
  const fields = {};
  form.querySelectorAll('input[name], select[name], textarea[name]').forEach(el => {
    if (!el.name || el.type === 'file') return;
    if (el.type === 'radio') { if (el.checked) fields[el.name] = el.value; return; }
    if (el.type === 'checkbox') { fields[el.name] = !!el.checked; return; }
    fields[el.name] = el.value;
  });
  return {
    version: 1,
    savedAt: new Date().toISOString(),
    currentStep,
    generatedRef,
    fields,
    uploadedPic: uploadedPic ? {
      name: uploadedPic.name,
      dataURL: uploadedPic.dataURL,
      restoredPreviewOnly: !uploadedPic.file,
    } : null,
  };
}

let isSubmissionSuccessful = false;

function saveProgress() {
  if (isRestoringProgress || isSubmissionSuccessful) return;
  try {
    const data = collectProgressData();
    if (data) localStorage.setItem(FORM_PROGRESS_KEY, JSON.stringify(data));
  } catch (err) {
    console.warn('Could not save application progress:', err);
  }
}

const saveProgressSoon = debounceProgress(saveProgress);

function applySavedFieldValues(fields) {
  if (!fields || typeof fields !== 'object') return;
  Object.entries(fields).forEach(([name, value]) => {
    const controls = Array.from(document.querySelectorAll(`[name="${CSS.escape(name)}"]`));
    controls.forEach(el => {
      if (el.type === 'file') return;
      if (el.type === 'radio') { el.checked = el.value === value; return; }
      if (el.type === 'checkbox') { el.checked = !!value; return; }
      el.value = value ?? '';
    });
  });
}

function restoreUploadedPicPreview(savedPic) {
  if (!savedPic || !savedPic.dataURL) return;
  uploadedPic = {
    name: savedPic.name || 'Saved 2x2 photo',
    dataURL: savedPic.dataURL,
    file: null,
    restoredPreviewOnly: true,
  };
  const zone = document.getElementById('picZone');
  if (zone) {
    zone.classList.add('uploaded');
    zone.innerHTML = `<input type="file" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%"><img src="${savedPic.dataURL}" alt="2x2 photo" style="width:100%;height:100%;object-fit:cover;border-radius:10px">`;
  }
}

function restoreProgress() {
  try {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('fresh')) { localStorage.removeItem(FORM_PROGRESS_KEY); return; }
    const raw = localStorage.getItem(FORM_PROGRESS_KEY);
    if (!raw) return;
    const data = JSON.parse(raw);
    if (!data || data.version !== 1) return;
    isRestoringProgress = true;
    applySavedFieldValues(data.fields);
    generatedRef = data.generatedRef || '';
    currentStep = Math.min(Math.max(parseInt(data.currentStep, 10) || 1, 1), TOTAL_STEPS);
    restoreUploadedPicPreview(data.uploadedPic);
  } catch (err) {
    console.warn('Could not restore application progress:', err);
  } finally {
    isRestoringProgress = false;
  }
}

function clearSavedProgress() {
  try { localStorage.removeItem(FORM_PROGRESS_KEY); }
  catch (err) { console.warn('Could not clear saved application progress:', err); }
}

function initProgressAutosave() {
  const form = document.getElementById('aForm');
  if (!form) return;
  form.addEventListener('input', saveProgressSoon);
  form.addEventListener('change', saveProgressSoon);
  window.addEventListener('beforeunload', saveProgress);
}

const DOCS_MAP = {
  'Freshmen': ['Original Report Card (Grade 11 & 12)', 'Original Diploma', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'Transferee': ['Official Transcript of Records', 'Certificate of Honorable Dismissal', 'Diploma / Cert. of Completed Courses', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'ALS Graduate': ['ALS A&E Certificate', 'ALS Test Results / Transcript', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character'],
  'Returnee': ['BTECH Official Transcript of Records', 'Certificate of Candidacy (if applicable)', 'Written Statement of Purpose', 'PSA Birth Certificate', '2×2 ID Picture', 'Certificate of Good Moral Character', 'Academic Clearance (prev. semester)']
};

const $ = sel => document.querySelector(sel);
const $$ = sel => document.querySelectorAll(sel);
const gv = name => { const el = $(`[name="${name}"]`); return el ? el.value : ''; };
const gr = name => { const el = $(`input[name="${name}"]:checked`); return el ? el.value : ''; };
const se = (id, msg) => { const el = document.getElementById(id); if (el) el.textContent = msg; };
const si = (id, html) => { const el = document.getElementById(id); if (el) el.innerHTML = html; };
const markErr = (name, on) => { const el = $(`[name="${name}"]`); if (el) el.classList.toggle('err', on); };
const showBanner = (id, on) => { const el = document.getElementById(id); if (el) el.classList.toggle('show', on); };

function buildStepper() {
  const row = document.getElementById('stepperRow');
  if (!row) return;
  row.innerHTML = '';
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'step-btn' +
      (i === currentStep ? ' active' : '') +
      (i < currentStep ? ' done' : '');
    btn.innerHTML = `<span class="snum">${i < currentStep ? '✓' : i}</span><span class="slbl">${STEP_LABELS[i - 1]}</span>`;
    btn.onclick = () => { if (i <= currentStep) { currentStep = i; show(); saveProgressSoon(); } };
    row.appendChild(btn);
    if (i < TOTAL_STEPS) {
      const conn = document.createElement('div');
      conn.className = 'step-conn' + (i < currentStep ? ' done' : '');
      row.appendChild(conn);
    }
  }
}

/* ─────────────────────────────────────────────────────────
   show() — renders the current step.
   The ONE line added vs the original:
     document.body.classList.toggle('step-12', currentStep === 12);
   This drives the CSS rule:
     body.step-12 .stepper-card { display: none !important; }
───────────────────────────────────────────────────────── */
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

  const deg = (pct / 100) * 360;
  const ring = document.getElementById('ring');
  if (ring) ring.style.background = `conic-gradient(#40916c ${deg}deg, rgba(255,255,255,.18) ${deg}deg)`;
  se('pctTxt', pct + '%');

  const prevBtn = document.getElementById('prevBtn');
  if (prevBtn) prevBtn.style.visibility = currentStep === 1 ? 'hidden' : 'visible';

  const navBar = document.getElementById('navBar');
  const nextBtn = document.getElementById('nextBtn');
  if (currentStep === TOTAL_STEPS) {
    if (navBar) navBar.style.display = 'none';
  } else {
    if (navBar) navBar.style.display = 'flex';
    if (nextBtn) {
      nextBtn.innerHTML = `Next <i data-iconsax="chevron-right" style="width:14px;height:14px;stroke-width:2.5"></i>`;
      if (typeof iconsax !== 'undefined') iconsax.createIcons({ props: { 'data-iconsax': 'chevron-right' }, nameAttr: 'data-iconsax' });
      nextBtn.className = 'bp';
      nextBtn.onclick = () => nav(1);
    }
  }

  /* ── Hide / show stepper based on current step ── */
  document.body.classList.toggle('step-12', currentStep === 12);

  buildStepper();
  updateAcadBlocks();
  if (currentStep === 12) buildReview();
  saveProgressSoon();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nav(dir) {
  if (dir === 1 && !validate(currentStep)) return;
  if (dir === 1) showToast();
  currentStep = Math.min(Math.max(currentStep + dir, 1), TOTAL_STEPS);
  show();
  saveProgressSoon();
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
      ['surname', 'e-sur'], ['firstName', 'e-fn'], ['placeOfBirth', 'e-pob'],
      ['motherFirstName', 'e-mfn'], ['motherMaidenName', 'e-mln'],
      ['fatherSurname', 'e-fls'], ['fatherFirstName', 'e-ffn'],
      ['civilStatus', 'e-cs']
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
    const email = gv('studentEmail').trim().toLowerCase();
    const emailOk = /^[^@]+@(gmail\.com|googlemail\.com|yahoo\.(com|com\.ph|co\.uk|co\.in|fr|es|de))$/i.test(email);
    if (!email) { se('e-email', 'Email is required'); markErr('studentEmail', true); ok = false; }
    else if (!emailOk) { se('e-email', 'Only Gmail or Yahoo addresses are accepted'); markErr('studentEmail', true); ok = false; }
    else { se('e-email', ''); markErr('studentEmail', false); }
    const studentContactOk = /^9\d{9}$/.test(gv('studentContactNumber'));
    se('e-scn', studentContactOk ? '' : 'Valid 10-digit number starting with 9');
    markErr('studentContactNumber', !studentContactOk);
    if (!studentContactOk) ok = false;
    showBanner('e2', !ok);
    return ok;
  }

  if (step === 3) {
    ['acadElem', 'acadHS', 'acadSHS', 'acadTertiary'].forEach(id => {
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
    [['permanentProvince', 'e-pp'], ['permanentCity', 'e-pc'], ['permanentBarangay', 'e-pb'], ['permanentZipCode', 'e-pz']].forEach(([field, errId]) => {
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
    [['presentProvince', 'e-rp'], ['presentCity', 'e-rc'], ['presentBarangay', 'e-rb'], ['presentZipCode', 'e-rz']].forEach(([field, errId]) => {
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
    se('e-g1', v1 ? '' : 'Enter a value between 70-100');
    se('e-g2', v2 ? '' : 'Enter a value between 70-100');
    markErr('grade11GWA', !v1);
    markErr('grade12GWA', !v2);
    ok = v1 && v2;
    if (ok) updateGWADisplay(g1, g2);
    showBanner('e7', !ok);
    return ok;
  }

  if (step === 9) {
    [['differentlyAbled', 'e-pwd'], ['soloParent', 'e-solo'], ['indigenous', 'e-indigenous'], ['fourPs', 'e-4ps']].forEach(([name, errId]) => {
      const valid = !!gr(name);
      se(errId, valid ? '' : 'Please select Yes or No');
      if (!valid) ok = false;
    });
    showBanner('e8', !ok);
    return ok;
  }

  if (step === 10) {
    const all = ['declarationTruth', 'dataPrivacy', 'authorizedProcessing']
      .every(n => { const el = $(`input[name="${n}"]`); return el && el.checked; });
    showBanner('e9', !all);
    return all;
  }

  if (step === 11) {
    const hasPic = !!uploadedPic;
    se('e-pic', hasPic ? '' : 'Please upload your 2x2 ID picture');
    se('e10t', 'Please upload your 2x2 ID picture.');
    ok = hasPic;
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
  const type = gr('respondentType');
  const shs = document.getElementById('acadSHS');
  const tertiary = document.getElementById('acadTertiary');
  const als = document.getElementById('acadALS');
  const returnee = document.getElementById('acadReturnee');
  if (shs) shs.style.display = 'block';
  if (tertiary) tertiary.style.display = 'none';
  if (als) als.style.display = 'none';
  if (returnee) returnee.style.display = 'none';
  if (type === 'Freshmen') {
    if (shs) shs.style.display = 'block';
    if (tertiary) tertiary.style.display = 'none';
    if (als) als.style.display = 'none';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'Transferee') {
    if (shs) shs.style.display = 'block';
    if (tertiary) tertiary.style.display = 'block';
    if (als) als.style.display = 'none';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'ALS Graduate') {
    if (shs) shs.style.display = 'none';
    if (tertiary) tertiary.style.display = 'none';
    if (als) als.style.display = 'block';
    if (returnee) returnee.style.display = 'none';
  } else if (type === 'Returnee') {
    if (shs) shs.style.display = 'block';
    if (tertiary) tertiary.style.display = 'none';
    if (als) als.style.display = 'none';
    if (returnee) returnee.style.display = 'block';
  }
}

function initSameAsPermanent() {
  const chk = document.getElementById('sameChk');
  if (!chk) return;
  const syncAddress = () => {
    copyAddressSelect('Province');
    copyAddressSelect('City');
    copyAddressSelect('Barangay');
    ['ZipCode', 'ProvincePsgcCode', 'CityPsgcCode', 'BarangayPsgcCode'].forEach(f => {
      const el = $(`[name="present${f}"]`);
      if (el) el.value = gv('permanent' + f);
    });
    ['Province', 'City', 'Barangay', 'ZipCode'].forEach(f => {
      const el = $(`[name="present${f}"]`);
      if (el) el.disabled = true;
    });
  };
  const clearAddress = () => {
    ['Province', 'City', 'Barangay', 'ZipCode', 'ProvincePsgcCode', 'CityPsgcCode', 'BarangayPsgcCode'].forEach(f => {
      const el = $(`[name="present${f}"]`);
      if (el) el.value = '';
    });
    const city = document.getElementById('presentCitySelect');
    const barangay = document.getElementById('presentBarangaySelect');
    if (city) { city.innerHTML = '<option value="">Select town / city</option>'; city.disabled = true; }
    if (barangay) { barangay.innerHTML = '<option value="">Select barangay</option>'; barangay.disabled = true; }
    const province = document.getElementById('presentProvinceSelect');
    if (province) province.disabled = false;
    const zip = $('[name="presentZipCode"]');
    if (zip) zip.disabled = false;
  };
  chk.addEventListener('change', function () { if (this.checked) syncAddress(); else clearAddress(); });
  ['Province', 'City', 'Barangay', 'ZipCode'].forEach(f => {
    const el = $(`[name="permanent${f}"]`);
    if (!el) return;
    el.addEventListener('change', () => { if (chk.checked) syncAddress(); });
    el.addEventListener('input', () => { if (chk.checked) syncAddress(); });
  });
}

function copyAddressSelect(field) {
  const source = $(`[name="permanent${field}"]`);
  const target = $(`[name="present${field}"]`);
  if (!source || !target) return;
  if (source.tagName.toLowerCase() === 'select' && target.tagName.toLowerCase() === 'select') {
    target.innerHTML = source.innerHTML;
    target.value = source.value;
    return;
  }
  target.value = source.value;
}

async function fetchPSGC(endpoint) {
  const url = (window.PSGC_API_BASE || 'https://psgc.gitlab.io/api') + endpoint;
  const res = await fetch(url, { method: 'GET', mode: 'cors', credentials: 'omit', headers: { 'Accept': 'application/json' }, referrerPolicy: 'no-referrer' });
  if (!res.ok) throw new Error('PSGC request failed');
  return res.json();
}

function fillSelect(selectEl, items, placeholder) {
  if (!selectEl) return;
  selectEl.innerHTML = `<option value="">${placeholder}</option>`;
  items.forEach(item => {
    const opt = document.createElement('option');
    opt.value = item.name;
    opt.textContent = item.name;
    opt.dataset.code = item.code;
    selectEl.appendChild(opt);
  });
}

function resetAddressLevel(prefix, level) {
  const city = document.getElementById(prefix + 'CitySelect');
  const brgy = document.getElementById(prefix + 'BarangaySelect');
  const cityCode = $(`[name="${prefix}CityPsgcCode"]`);
  const brgyCode = $(`[name="${prefix}BarangayPsgcCode"]`);
  if (level === 'province') {
    if (city) { city.innerHTML = '<option value="">Select town / city</option>'; city.disabled = true; city.value = ''; }
    if (brgy) { brgy.innerHTML = '<option value="">Select barangay</option>'; brgy.disabled = true; brgy.value = ''; }
    if (cityCode) cityCode.value = '';
    if (brgyCode) brgyCode.value = '';
  } else if (level === 'city') {
    if (brgy) { brgy.innerHTML = '<option value="">Select barangay</option>'; brgy.disabled = true; brgy.value = ''; }
    if (brgyCode) brgyCode.value = '';
  }
}

function bindAddressPSGC(prefix) {
  const provinceSelect = document.getElementById(prefix + 'ProvinceSelect');
  const citySelect = document.getElementById(prefix + 'CitySelect');
  const barangaySelect = document.getElementById(prefix + 'BarangaySelect');
  const provinceCode = $(`[name="${prefix}ProvincePsgcCode"]`);
  const cityCode = $(`[name="${prefix}CityPsgcCode"]`);
  const barangayCode = $(`[name="${prefix}BarangayPsgcCode"]`);
  if (!provinceSelect || !citySelect || !barangaySelect) return;
  provinceSelect.addEventListener('change', async function () {
    const selected = this.options[this.selectedIndex];
    const psgcCode = selected && selected.dataset ? selected.dataset.code || '' : '';
    if (provinceCode) provinceCode.value = psgcCode;
    resetAddressLevel(prefix, 'province');
    if (!psgcCode) return;
    try {
      const cities = await fetchPSGC('/provinces/' + psgcCode + '/cities-municipalities/');
      fillSelect(citySelect, cities, 'Select town / city');
      citySelect.disabled = false;
    } catch (_) { fillSelect(citySelect, [], 'Unable to load cities'); citySelect.disabled = true; }
  });
  citySelect.addEventListener('change', async function () {
    const selected = this.options[this.selectedIndex];
    const psgcCode = selected && selected.dataset ? selected.dataset.code || '' : '';
    const cityName = selected ? selected.value || '' : '';
    if (cityCode) cityCode.value = psgcCode;
    autoFillZip(prefix, cityName);
    resetAddressLevel(prefix, 'city');
    if (!psgcCode) return;
    try {
      const barangays = await fetchPSGC('/cities-municipalities/' + psgcCode + '/barangays/');
      fillSelect(barangaySelect, barangays, 'Select barangay');
      barangaySelect.disabled = false;
    } catch (_) { fillSelect(barangaySelect, [], 'Unable to load barangays'); barangaySelect.disabled = true; }
  });
  barangaySelect.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const psgcCode = selected && selected.dataset ? selected.dataset.code || '' : '';
    if (barangayCode) barangayCode.value = psgcCode;
  });
}

function autoFillZip(prefix, cityName) {
  const zipInput = $(`[name="${prefix}ZipCode"]`);
  if (!zipInput || !cityName) return;
  const zip = ZIP_BY_CITY[cityName];
  if (!zip) return;
  zipInput.value = zip;
}

async function initPSGCLocation() {
  try {
    const provinces = await fetchPSGC('/provinces/');
    const permanentProvince = document.getElementById('permanentProvinceSelect');
    const presentProvince = document.getElementById('presentProvinceSelect');
    fillSelect(permanentProvince, provinces, 'Select province');
    fillSelect(presentProvince, provinces, 'Select province');
  } catch (_) {
    const permanentProvince = document.getElementById('permanentProvinceSelect');
    const presentProvince = document.getElementById('presentProvinceSelect');
    if (permanentProvince) permanentProvince.innerHTML = '<option value="">Unable to load provinces</option>';
    if (presentProvince) presentProvince.innerHTML = '<option value="">Unable to load provinces</option>';
  }
  bindAddressPSGC('permanent');
  bindAddressPSGC('present');
}

function initGWALive() {
  ['grade11GWA', 'grade12GWA'].forEach(name => {
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
  $$('[name="elemYear"], [name="alsYear"], [name="returneeYear"], [name="hsYear"], [name="shsYear"], [name="tertiaryYear"]').forEach(el => {
    el.addEventListener('input', () => { el.value = el.value.replace(/\D/g, '').slice(0, 4); });
  });
}

function handlePic(input) {
  const file = input.files[0];
  if (!file) return;
  if (!file.type.match(/image\/(jpeg|png|jpg)/)) { se('e-pic', 'Only JPG/PNG files accepted'); return; }
  if (file.size > 5 * 1024 * 1024) { se('e-pic', 'File must be under 5MB'); return; }
  const reader = new FileReader();
  reader.onload = e => {
    uploadedPic = { name: file.name, dataURL: e.target.result, file: file };
    const zone = document.getElementById('picZone');
    if (!zone) return;
    zone.classList.add('uploaded');
    zone.innerHTML = `<input type="file" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%"><img src="${e.target.result}" alt="2x2 photo" style="width:100%;height:100%;object-fit:cover;border-radius:10px">`;
    se('e-pic', '');
    showBanner('e10', false);
  };
  reader.readAsDataURL(file);
}

function buildReview() {
  if (!generatedRef) {
    generatedRef = 'BTECH-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
  }
  const today = new Date().toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
  se('rv-ref', generatedRef);
  se('rv-ref2', generatedRef);
  se('rv-date', today);

  const picBox = document.getElementById('rv-picBox');
  if (picBox) {
    picBox.innerHTML = uploadedPic
      ? `<img src="${uploadedPic.dataURL}" alt="2x2 photo" style="width:100%;height:100%;object-fit:cover">`
      : `<span class="rs-pic-placeholder">2x2<br>ID Photo</span><div class="rs-pic-label">APPLICANT PHOTO</div>`;
  }

  se('rv-surname', gv('surname').toUpperCase() || '-');
  se('rv-firstName', gv('firstName').toUpperCase() || '-');
  se('rv-middleName', gv('middleName').toUpperCase() || '-');
  se('rv-suffix', gv('suffix') || '-');

  const fSuf = gv('fatherSuffix');
  const fFull = [gv('fatherFirstName'), gv('fatherMiddleName'), gv('fatherSurname'), (fSuf && fSuf !== 'N/A' ? fSuf : '')].filter(Boolean).join(' ').toUpperCase();
  se('rv-fatherName', fFull || '-');

  const rawDob = gv('dateOfBirth');
  if (rawDob) {
    const d = new Date(rawDob + 'T00:00:00');
    se('rv-dob', d.toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' }));
  } else se('rv-dob', '-');
  se('rv-pob', gv('placeOfBirth') || '-');

  const sex = gr('sex');
  se('rv-male', sex === 'Male' ? '☑ Male' : '☐ Male');
  se('rv-female', sex === 'Female' ? '☑ Female' : '☐ Female');

  const buildAddr = prefix => [
    gv(`${prefix}Barangay`) ? `Brgy. ${gv(`${prefix}Barangay`)}` : '',
    gv(`${prefix}City`), gv(`${prefix}Province`), gv(`${prefix}ZipCode`)
  ].filter(Boolean).join(', ') || '-';

  se('rv-permAddr', buildAddr('permanent'));
  se('rv-presAddr', buildAddr('present'));
  se('rv-fContact', gv('contactNumber') ? '+63 ' + gv('contactNumber') : '-');
  se('rv-mContact', gv('alternateContactNumber') ? '+63 ' + gv('alternateContactNumber') : '-');

  const mFull = [gv('motherFirstName'), gv('motherMiddleName'), gv('motherMaidenName')].filter(Boolean).join(' ').toUpperCase();
  se('rv-motherName', mFull || '-');

  se('rv-course1', gr('firstChoice') || '-');
  se('rv-course2', gr('secondChoice') || '-');
  const g1 = parseFloat(gv('grade11GWA'));
  const g2 = parseFloat(gv('grade12GWA'));
  se('rv-g11', isNaN(g1) ? '-' : g1.toFixed(2));
  se('rv-g12', isNaN(g2) ? '-' : g2.toFixed(2));
  if (!isNaN(g1) && !isNaN(g2)) {
    const avg = ((g1 + g2) / 2).toFixed(2);
    se('rv-gwa', avg);
    se('rv-gwarmk', avg >= 90 ? 'Outstanding' : avg >= 85 ? 'Very Satisfactory' : avg >= 80 ? 'Satisfactory' : 'Passing');
  }

  se('rv-type', gr('respondentType') || '-');
  const badge = (val, yesLabel, noLabel) =>
    `<span class="rs-badge${val === 'yes' ? '' : ' no'}">${val === 'yes' ? yesLabel : noLabel}</span>`;
  si('rv-pwd', badge(gr('differentlyAbled'), 'Yes - PWD', 'No'));
  si('rv-solo', badge(gr('soloParent'), 'Yes', 'No'));
  si('rv-indigenous', badge(gr('indigenous'), 'Yes', 'No'));
  si('rv-4ps', badge(gr('fourPs'), 'Yes - 4Ps', 'No'));

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

function trySubmit() { openModal('moConfirm'); }

function setSubmitState(isSubmitting) {
  const confirmBtn = document.getElementById('submitBtn');
  if (confirmBtn) {
    confirmBtn.disabled = isSubmitting;
    confirmBtn.innerHTML = isSubmitting
      ? '<i data-iconsax="refresh" style="width:13px;height:13px"></i>Submitting...'
      : '<i data-iconsax="check" style="width:13px;height:13px"></i>Confirm &amp; Submit';
  }
  document.querySelectorAll('.review-action-bar .bsub').forEach(function (button) {
    button.disabled = isSubmitting;
    button.innerHTML = isSubmitting
      ? '<i data-iconsax="refresh" style="width:14px;height:14px"></i>Submitting...'
      : '<i data-iconsax="check" style="width:14px;height:14px"></i>Submit Application';
  });
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
}

function buildAddress(prefix) {
  const parts = [
    gv(prefix + 'Barangay') ? 'Brgy. ' + gv(prefix + 'Barangay') : '',
    gv(prefix + 'City'),
    gv(prefix + 'Province'),
    gv(prefix + 'ZipCode')
  ].filter(Boolean);
  return parts.join(', ') || '';
}

function applicationTypeFromRespondent(type) {
  const map = { 'Freshmen': 'new', 'Transferee': 'transferee', 'Returnee': 'returnee', 'ALS Graduate': 'new' };
  return map[type] || 'new';
}

async function doSubmit() {
  if (isSubmittingGlobal) return;
  closeModal('moConfirm');
  if (typeof AdmissionAPI === 'undefined') { alert('Cannot submit: API is not loaded.'); return; }
  try {
    isSubmittingGlobal = true;
    setSubmitState(true);
    const year = new Date().getFullYear();
    const payload = {
      email: gv('studentEmail').trim(),
      first_name: gv('firstName').trim(),
      last_name: gv('surname').trim(),
      middle_name: gv('middleName').trim() || null,
      suffix: gv('suffix').trim() || null,
      sex: (gr('sex') || '').toLowerCase() === 'male' ? 'Male' : (gr('sex') || '').toLowerCase() === 'female' ? 'Female' : 'prefer_not_to_say',
      date_of_birth: gv('dateOfBirth'),
      place_of_birth: gv('placeOfBirth').trim() || null,
      civil_status: gv('civilStatus') || null,
      contact_number: (function () {
        const raw = String(gv('studentContactNumber') || gv('contactNumber') || '').replace(/\D/g, '');
        const withZero = raw.length > 0 ? ('0' + raw).slice(-11) : '';
        return withZero ? withZero.slice(0, 20) : null;
      })(),
      permanent_address: buildAddress('permanent') || gv('permanentCity') + ', ' + gv('permanentProvince'),
      present_address: buildAddress('present') || gv('presentCity') + ', ' + gv('presentProvince'),
      elementary_school: gv('elemSchool').trim() || null,
      elementary_year_graduated: parseInt(gv('elemYear'), 10) || null,
      junior_high_school: gv('hsSchool').trim() || null,
      junior_high_year_graduated: parseInt(gv('hsYear'), 10) || null,
      senior_high_school: gv('shsSchool').trim() || null,
      senior_high_year_graduated: parseInt(gv('shsYear'), 10) || null,
      previous_college: gr('respondentType') === 'Transferee' ? gv('tertiarySchool').trim() : null,
      previous_college_year_last_attended: gr('respondentType') === 'Transferee' ? parseInt(gv('tertiaryYear'), 10) : null,
      father_name: [gv('fatherFirstName'), gv('fatherMiddleName'), gv('fatherSurname')].filter(Boolean).join(' ').trim() || null,
      father_suffix: (gv('fatherSuffix') && gv('fatherSuffix') !== 'N/A') ? gv('fatherSuffix') : null,
      father_contact: (function () {
        const raw = String(gv('contactNumber') || '').replace(/\D/g, '');
        return raw.length >= 10 ? raw.slice(0, 20) : null;
      })(),
      mother_name: [gv('motherFirstName'), gv('motherMiddleName'), gv('motherMaidenName')].filter(Boolean).join(' ').trim() || null,
      mother_contact: (function () {
        const raw = String(gv('alternateContactNumber') || '').replace(/\D/g, '');
        return raw.length >= 10 ? raw.slice(0, 20) : null;
      })(),
      gwa_grade_11: (function () { const n = parseFloat(gv('grade11GWA')); return Number.isFinite(n) ? String(n) : null; })(),
      gwa_grade_12: (function () { const n = parseFloat(gv('grade12GWA')); return Number.isFinite(n) ? String(n) : null; })(),
      first_choice: gr('firstChoice') || null,
      second_choice: gr('secondChoice') || null,
      academic_year: year + '-' + (year + 1),
      semester: '1st',
      applicant_type: gr('respondentType') || null,
      pwd: gr('differentlyAbled') || null,
      solo_parent: gr('soloParent') || null,
      indigenous: gr('indigenous') || null,
      four_ps: gr('fourPs') || null,
      admin_notes: null,
      reference_number: generatedRef
    };

    const data = await AdmissionAPI.submitPublic(payload);
    generatedRef = data.reference_number || generatedRef;
    se('refNum', generatedRef);
    isSubmissionSuccessful = true;
    clearSavedProgress();
    openModal('moSuccess');
    uploadSubmittedFiles(data);
  } catch (err) {
    let msg = err.message || 'Submission failed. Please try again.';
    const errors = (err.data && err.data.errors) || null;
    if (errors && typeof errors === 'object') {
      const list = [];
      Object.keys(errors).forEach(f => {
        const arr = errors[f];
        if (Array.isArray(arr) && arr[0]) list.push(arr[0]);
      });
      if (list.length) msg = 'Validation failed:\n\n' + list.join('\n');
    }
    alert(msg);
  } finally {
    isSubmittingGlobal = false;
    setSubmitState(false);
  }
}

async function uploadSubmittedFiles(data) {
  if (typeof AdmissionAPI === 'undefined') return;
  const applicationId = data && (data.id || data.application_id);
  if (!applicationId) return;
  const uploadToken = data && data.upload_token;
  if (uploadedPic && uploadedPic.file) {
    try { await AdmissionAPI.uploadDocument(applicationId, 'id_photo', uploadedPic.file, uploadToken); }
    catch (err) { console.warn('ID photo upload failed after submission:', err); }
  }
  if (uploadedDocs.length > 0) {
    for (const doc of uploadedDocs) {
      if (!doc.file) continue;
      try { await AdmissionAPI.uploadDocument(applicationId, 'other', doc.file, uploadToken); }
      catch (err) { console.warn('Supporting document upload failed after submission:', err); }
    }
  }
}

function resetForm() {
  const form = document.getElementById('aForm');
  if (form) form.reset();
  uploadedPic = null;
  uploadedDocs = [];
  generatedRef = '';
  currentStep = 1;
  const zone = document.getElementById('picZone');
  if (zone) {
    zone.classList.remove('uploaded');
    zone.innerHTML = `<input type="file" id="picInput" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)"><i data-iconsax="image" style="width:30px;height:30px;color:#b5cce4;stroke-width:1.8"></i><span style="font-size:11px;color:#b5cce4;font-weight:700;margin-top:6px">Click to upload</span>`;
    if (typeof iconsax !== 'undefined') iconsax.createIcons();
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

function showReqModal(type) { showReq(type); }

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

const BOARD_EXAM_CATEGORIES = ['Education', 'Accountancy'];

function renderProgramRadios(containerId, name, excludeBoardExam) {
  const el = document.getElementById(containerId);
  if (!el) return;
  if (typeof AdmissionAPI === 'undefined') {
    el.innerHTML = '<p style="color:#b91c1c;font-size:13px">Load this page from your server so the API can fetch programs.</p>';
    return;
  }
  el.innerHTML = '<p style="color:#6b7280;font-size:13px">Loading programs…</p>';
  AdmissionAPI.getPrograms()
    .then(programs => {
      if (!Array.isArray(programs) || programs.length === 0) {
        el.innerHTML = '<p style="color:#6b7280;font-size:13px;margin-bottom:8px">No programs available.</p>' +
          '<p style="color:#6b7280;font-size:12px;margin-bottom:8px">Seed the database or add programs in the admin dashboard.</p>' +
          '<button type="button" class="retry-programs-btn" style="font-size:12px;padding:6px 12px;background:var(--navy);color:white;border:none;border-radius:6px;cursor:pointer">Retry</button>';
        const btn = el.querySelector('.retry-programs-btn');
        if (btn) btn.addEventListener('click', () => renderProgramRadios(containerId, name, excludeBoardExam));
        return;
      }
      let visible = programs;
      if (excludeBoardExam) {
        visible = visible.filter(p => {
          const cat = (p.category || '').trim();
          return !BOARD_EXAM_CATEGORIES.some(b => cat.toLowerCase() === b.toLowerCase());
        });
      }
      const programState = p => {
        const status = (p.status || '').toLowerCase();
        const slotsLeft = Number(p.slots_left ?? 0);
        const byStatus = status !== 'inactive' && status !== 'disabled' && status !== 'closed';
        const byFlag = p.is_active !== false;
        const bySlots = Number.isFinite(slotsLeft) ? slotsLeft > 0 : true;
        const selectable = byStatus && byFlag && bySlots;
        const reason = !byFlag || !byStatus ? 'Admissions closed' : (!bySlots ? 'Full slot' : '');
        return { selectable, reason, slotsLeft };
      };
      if (visible.length === 0) {
        el.innerHTML = '<p style="color:#6b7280;font-size:13px">No programs available at this time.</p>';
        return;
      }
      const selectableCount = visible.filter(p => programState(p).selectable).length;
      const notice = selectableCount < visible.length
        ? '<p style="font-size:12px;color:#92400e;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:8px 10px;margin-bottom:10px">Programs marked closed or full cannot be selected.</p>'
        : '';
      el.innerHTML = notice + visible.map(p => {
        const state = programState(p);
        const isBoardExam = BOARD_EXAM_CATEGORIES.some(b => (p.category || '').toLowerCase() === b.toLowerCase());
        const badge = (isBoardExam && !excludeBoardExam)
          ? ' <span style="font-size:10px;font-weight:700;color:#7c3aed;background:#ede9fe;padding:2px 7px;border-radius:99px;margin-left:6px;vertical-align:middle">Board Exam</span>'
          : '';
        const availabilityBadge = state.selectable
          ? `<span style="font-size:10px;font-weight:700;color:#047857;background:#d1fae5;padding:2px 7px;border-radius:99px;margin-left:6px;vertical-align:middle">${state.slotsLeft} slots left</span>`
          : `<span style="font-size:10px;font-weight:700;color:#b91c1c;background:#fee2e2;padding:2px 7px;border-radius:99px;margin-left:6px;vertical-align:middle">${state.reason}</span>`;
        const disabledStyle = state.selectable ? '' : 'opacity:.62;cursor:not-allowed;background:#f8fafc;';
        return `<label class="oc" style="${disabledStyle}"><input type="radio" name="${name}" value="${escapeHtml(p.name)}" ${state.selectable ? '' : 'disabled'} style="width:15px;height:15px;accent-color:var(--navy);margin-top:2px"><span style="margin-left:10px;font-size:13px;font-weight:600">${escapeHtml(p.name)}${badge}${availabilityBadge}</span></label>`;
      }).join('');
      initCourseConflict();
    })
    .catch(() => {
      el.innerHTML = '<p style="color:#b91c1c;font-size:13px;margin-bottom:8px">Failed to load programs. Check that the backend is running.</p>' +
        '<button type="button" class="retry-programs-btn" style="font-size:12px;padding:6px 12px;background:var(--navy);color:white;border:none;border-radius:6px;cursor:pointer">Retry</button>';
      const btn = el.querySelector('.retry-programs-btn');
      if (btn) btn.addEventListener('click', () => renderProgramRadios(containerId, name, excludeBoardExam));
    });
}

function escapeHtml(s) {
  const div = document.createElement('div');
  div.textContent = s;
  return div.innerHTML;
}

function loadPrograms() {
  renderProgramRadios('firstChoiceList', 'firstChoice', false);
  renderProgramRadios('secondChoiceList', 'secondChoice', true);
}

async function initPublicSettings() {
  if (typeof AdmissionAPI === 'undefined') return;
  try {
    const s = await AdmissionAPI.getPublicSettings();
    if (s.accept_applications === '0' || s.accept_applications === 0) {
      document.body.innerHTML = `
        <div style="height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:sans-serif;padding:20px;text-align:center;background:#f8fafc">
          <div style="width:80px;height:80px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px">
            <i data-iconsax="lock" style="color:#ef4444;width:40px;height:40px"></i>
          </div>
          <h1 style="color:#1e293b;margin-bottom:10px;font-size:24px;font-weight:700">Applications are currently closed</h1>
          <p style="color:#64748b;max-width:400px;line-height:1.6">The online admission application for ${s.school_year || 'the current term'} is not yet open or has already ended.</p>
          <a href="/" style="margin-top:24px;color:#254d82;text-decoration:none;font-weight:600;display:flex;align-items:center;gap:6px">
            <i data-iconsax="arrow-left" style="width:16px;height:16px"></i>
            Back to Home
          </a>
        </div>
      `;
      if (typeof iconsax !== 'undefined') iconsax.createIcons();
      return;
    }
    const instName = s.institution_name || 'Baliwag Polytechnic College';
    const instAddr = s.campus_address || 'Baliwag City, Bulacan, Philippines';
    const instEmail = s.admissions_email || 'admissions@btech.edu.ph';
    const h1 = document.querySelector('.hdr-text h1');
    if (h1) h1.textContent = instName + ' Admissions Office';
    const rsName = document.querySelector('.rs-school-name');
    if (rsName) rsName.textContent = instName.toUpperCase();
    const rsAddr = document.querySelector('.rs-addr');
    if (rsAddr) rsAddr.innerHTML = `${instAddr} &nbsp;|&nbsp; Email: ${instEmail}<br>Admission Period: ${s.school_year || 'S.Y. 2026-2027'}`;
    const rsFooterLeft = document.querySelector('.rs-footer-left');
    if (rsFooterLeft) rsFooterLeft.innerHTML = `${instName} - Office of Admissions &nbsp;|&nbsp; For Official Use Only`;
  } catch (e) {
    console.warn('Could not load public settings:', e);
  }
}

function showReq(type) {
  const reqs = DOCS_MAP[type] || [];
  const modal = document.getElementById('moReq');
  const list = document.getElementById('moReqList');
  const title = document.getElementById('moReqTitle');
  if (!modal || !list || !title) return;
  title.textContent = `${type} - Required Documents`;
  if (reqs.length) {
    list.innerHTML = reqs.map(r => `
      <div class="req-item-card">
        <i data-iconsax="info-circle"></i>
        <span>${r}</span>
      </div>
    `).join('');
  } else {
    list.innerHTML = '<p style="text-align:center;color:#64748b;font-size:13px">No requirements listed for this category.</p>';
  }
  if (typeof iconsax !== 'undefined') iconsax.createIcons();
  openModal('moReq');
}

function init() {
  restoreProgress();
  initPublicSettings();
  initSameAsPermanent();
  initPSGCLocation();
  initGWALive();
  initCourseConflict();
  initDigitOnly();
  initModalClose();
  initProgressAutosave();
  loadPrograms();
  $$('input[name="respondentType"]').forEach(r => r.addEventListener('change', updateAcadBlocks));
  show();
}

document.addEventListener('DOMContentLoaded', init);