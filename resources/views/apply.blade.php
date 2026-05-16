<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BTECH Online Admission Application</title>
  @include('partials.iconsax')
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link rel="stylesheet" href="{{ asset('css/Inquire-Now.css') }}?v=6">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .mo { will-change: opacity; transform: translateZ(0); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
    .mb { will-change: transform, opacity; transform: translateZ(0) scale(.92) translateY(24px); }
    .mo.show .mb { transform: translateZ(0) scale(1) translateY(0); }
    .acad-block { margin-bottom: 6px; }
    .acad-block:last-child { margin-bottom: 0; }
  </style>
</head>
<body>
  @include('partials.site-loader')

<div class="save-toast no-print" id="saveToast">
  <i data-iconsax="check" style="width:14px;height:14px;stroke-width:3"></i>
  Progress saved
</div>

@if(($settings['accept_applications'] ?? '1') === '0')
<div class="fixed inset-0 z-[9999] bg-[#0f1e3d]/90 backdrop-blur-xl flex items-center justify-center p-6 text-center">
  <div class="max-w-md w-full bg-white p-10 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-white/10 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-orange-500"></div>
    <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
      <i data-iconsax="lock" class="text-red-500 w-12 h-12"></i>
    </div>
    <h2 class="text-3xl font-bold text-[#0f1e3d] mb-4 tracking-tight">Application is already closed</h2>
    <p class="text-slate-500 mb-10 text-lg leading-relaxed">The application portal is currently closed. We are not accepting new submissions at this time.</p>
    <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3 w-full px-8 py-4 bg-[#0f1e3d] text-white rounded-2xl font-bold hover:bg-[#1b3557] hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-[#0f1e3d]/20">
      <i data-iconsax="home" class="w-5 h-5"></i>
      Return to Homepage
    </a>
  </div>
</div>
@endif

<header class="hdr no-print">
  <div class="hdr-inner">
    <div class="hdr-brand">
      <div class="hdr-logo">
        <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
        <i data-iconsax="graduation-cap" style="display:none;color:white;width:26px;height:26px"></i>
      </div>
      <div class="hdr-text">
        <h1>BTECH Admissions Office</h1>
        <p>Dalubhasaang Politekniko ng Lungsod ng Baliwag</p>
      </div>
    </div>

    <div class="hdr-actions">
      <a href="{{ route('home') }}" class="bs" style="font-size:12px;padding:8px 16px;border-radius:89px;background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);color:white;display:flex;align-items:center;gap:6px">
        <i data-iconsax="arrow-left" style="width:14px;height:14px;stroke-width:2.5"></i>
        Back to Home
      </a>
      <div class="c-ring" id="ring"><span id="pctTxt">0%</span></div>
    </div>

  </div>
</header>

<main class="main-wrap">



  <div class="stepper-card no-print">
    <div class="stepper-meta">
      <h2>Application Progress</h2>
      <span>Step <span id="stepLbl">1</span> of 12</span>
    </div>
    <div class="stepper-row" id="stepperRow"></div>
    <div class="prog-track">
      <div class="prog-fill" id="pbar" style="width:8%"></div>
    </div>
  </div>

  <form id="aForm" novalidate>

    <!-- STEP 1 -->
    <div class="sc active" data-step="1">
      <div class="fcard">
        <div class="fhdr"><h2>Step 1: Type of Respondent</h2><p>Select the category that best describes you</p></div>
        <div class="fbdy">
          <p style="color:#6b7280;font-size:13px;margin-bottom:18px">Your selection helps customize the academic information requested later in this form.</p>
          <label class="oc" style="align-items:center"><input type="radio" name="respondentType" value="Freshmen" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--navy)"><div style="margin-left:14px;flex:1"><div style="font-weight:700;color:#1f2937">Freshmen</div><p style="font-size:12px;color:#6b7280;margin-top:2px">High school graduate applying for the first time</p></div><button type="button" class="bs" style="font-size:11px;padding:6px 12px;border-radius:99px;margin-left:auto" onclick="showReq('Freshmen')">View Requirements</button></label>
          <label class="oc" style="align-items:center"><input type="radio" name="respondentType" value="Transferee" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--navy)"><div style="margin-left:14px;flex:1"><div style="font-weight:700;color:#1f2937">Transferee</div><p style="font-size:12px;color:#6b7280;margin-top:2px">Transferring from another higher education institution</p></div><button type="button" class="bs" style="font-size:11px;padding:6px 12px;border-radius:99px;margin-left:auto" onclick="showReq('Transferee')">View Requirements</button></label>
          <label class="oc" style="align-items:center"><input type="radio" name="respondentType" value="ALS Graduate" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--navy)"><div style="margin-left:14px;flex:1"><div style="font-weight:700;color:#1f2937">ALS Graduate</div><p style="font-size:12px;color:#6b7280;margin-top:2px">Alternative Learning System graduate with A&amp;E Certificate</p></div><button type="button" class="bs" style="font-size:11px;padding:6px 12px;border-radius:99px;margin-left:auto" onclick="showReq('ALS Graduate')">View Requirements</button></label>
          <label class="oc" style="align-items:center"><input type="radio" name="respondentType" value="Returnee" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;accent-color:var(--navy)"><div style="margin-left:14px;flex:1"><div style="font-weight:700;color:#1f2937">Returnee</div><p style="font-size:12px;color:#6b7280;margin-top:2px">Previously enrolled at BTECH, returning after a study break</p></div><button type="button" class="bs" style="font-size:11px;padding:6px 12px;border-radius:99px;margin-left:auto" onclick="showReq('Returnee')">View Requirements</button></label>
          <div class="eb" id="e1"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please select a respondent type to continue.</div>
        </div>
      </div>
    </div>

    <!-- STEP 2 -->
    <div class="sc" data-step="2">
      <div class="fcard">
      <div class="fhdr"><h2>Step 2: Personal Information</h2><p>As they appear on official government documents</p></div>
      <div class="fbdy">
      <p class="stitle">Student Details</p>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px"><i data-iconsax="alert-circle" style="width:16px;height:16px;color:#10b981"></i><p style="font-size:12px;color:#16a34a;margin:0">In Place Of Birth Only Put Province and City name, not the Specific Address. (ex. Pulilan, Bulacan)</p></div>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div><label class="fl">Surname <span style="color:var(--red)">*</span></label><input type="text" name="surname" placeholder="e.g. Dela Cruz" class="fi"><span class="et" id="e-sur"></span></div>
      <div><label class="fl">First Name <span style="color:var(--red)">*</span></label><input type="text" name="firstName" placeholder="e.g. Juan" class="fi"><span class="et" id="e-fn"></span></div>
      <div><label class="fl">Middle Name  <span style="color:var(--red)">*</span> </label><input type="text" name="middleName" placeholder="e.g. Santos" class="fi"></div>
      <div>
        <label class="fl">Suffix</label>
        <select name="suffix" class="fi" aria-label="Suffix">
          <option value="N/A" selected>N/A</option>
          <option value="Jr.">Jr.</option>
          <option value="Sr.">Sr.</option>
          <option value="II">II</option>
          <option value="III">III</option>
          <option value="IV">IV</option>
          <option value="V">V</option>
        </select>
      </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3" style="margin-top:12px">
      <div>
        <label class="fl">Sex <span style="color:var(--red)">*</span></label>
        <div style="display:flex;gap:8px">
        <label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="sex" value="Male" style="width:16px;height:16px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Male</span></label>
        <label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="sex" value="Female" style="width:16px;height:16px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Female</span></label>
        </div>
        <span class="et" id="e-sex"></span>
      </div>
      <div><label class="fl">Date of Birth <span style="color:var(--red)">*</span></label><input type="date" name="dateOfBirth" class="fi"><span class="et" id="e-dob"></span></div>
      <div><label class="fl">Place of Birth <span style="color:var(--red)">*</span></label><input type="text" name="placeOfBirth" placeholder="City / Province" class="fi"><span class="et" id="e-pob"></span></div>
      </div>
      <div class="personal-contact-grid" style="margin-top:12px">
        <div>
          <label class="fl">Civil Status <span style="color:var(--red)">*</span></label>
          <select 
            name="civilStatus" 
            class="fi" 
            style="padding-right:36px"
          >
            <option value="">Select civil status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Divorced">Divorced</option>
            <option value="Widowed">Widowed</option>
          </select>
          <span class="et" id="e-cs"></span>
        </div>
        <div><label class="fl">Contact Number <span style="color:var(--red)">*</span></label><div class="phone-wrap"><span class="ppfx">+63</span><input type="tel" name="studentContactNumber" placeholder="9XXXXXXXXX" class="fi phi" maxlength="10"></div><p style="font-size:11px;color:#9aa5b1;margin-top:4px">10-digit mobile number starting with 9</p><span class="et" id="e-scn"></span></div>
        <div><label class="fl">Email Address <span style="color:var(--red)">*</span></label><input type="email" name="studentEmail" placeholder="e.g. juan.delacruz@gmail.com" class="fi" title="Only Gmail or Yahoo addresses are accepted"><p style="font-size:11px;color:#9aa5b1;margin-top:4px">Only Gmail or Yahoo addresses accepted</p><span class="et" id="e-email"></span></div>
      </div>
      <div id="marriageNoteDiv" style="display:none;background:#fff3cd;border:1.5px solid #ffc107;border-radius:8px;padding:10px 12px;margin-top:12px">
        <p style="font-size:12px;color:#856404;font-weight:600;margin-bottom:4px">âš  Marriage Certificate Required</p>
        <p style="font-size:11px;color:#856404">Please upload a scanned copy of your marriage certificate in the documents section.</p>
      </div>
          <p class="stitle">Mother's Information <span style="font-size:11px;font-weight:400;color:#9aa5b1;text-transform:none">(Refer to your PSA Birth Certificate)</span></p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div><label class="fl">First Name <span style="color:var(--red)">*</span></label><input type="text" name="motherFirstName" placeholder="First name" class="fi"><span class="et" id="e-mfn"></span></div>
            <div><label class="fl">Middle Name</label><input type="text" name="motherMiddleName" placeholder="Middle name" class="fi"></div>
            <div><label class="fl">Maiden Surname <span style="color:var(--red)">*</span></label><input type="text" name="motherMaidenName" placeholder="Maiden surname" class="fi"><span class="et" id="e-mln"></span></div>
          </div>
          <p class="stitle">Father's Information</p>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div><label class="fl">Surname <span style="color:var(--red)">*</span></label><input type="text" name="fatherSurname" placeholder="Surname" class="fi"><span class="et" id="e-fls"></span></div>
            <div><label class="fl">First Name <span style="color:var(--red)">*</span></label><input type="text" name="fatherFirstName" placeholder="First name" class="fi"><span class="et" id="e-ffn"></span></div>
            <div><label class="fl">Middle Name</label><input type="text" name="fatherMiddleName" placeholder="Middle name" class="fi"></div>
          </div>
          <div class="eb" id="e2"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please fill in all required fields.</div>
        </div>
      </div>
    </div>
  </div>

    <!-- STEP 3 -->
    <div class="sc" data-step="3">
      <div class="fcard">
        <div class="fhdr"><h2>Step 3: Academic Information</h2><p>Provide your academic background and credentials</p></div>
        <div class="fbdy">
          <div id="acadElem" class="acad-block"><p class="stitle">Elementary School</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">School Name <span style="color:var(--red)">*</span></label><input type="text" name="elemSchool" placeholder="e.g. Baliwag Elementary School" class="fi"></div><div><label class="fl">Year Graduated <span style="color:var(--red)">*</span></label><input type="number" name="elemYear" placeholder="e.g. 2016" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div></div>
                    <div id="acadALS" class="acad-block" style="display:none"><p class="stitle">ALS (Alternative Learning System)</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">Learning Center Name <span style="color:var(--red)">*</span></label><input type="text" name="alsCenter" placeholder="e.g. Baliwag ALS Center" class="fi"></div><div><label class="fl">Year Completed <span style="color:var(--red)">*</span></label><input type="number" name="alsYear" placeholder="e.g. 2023" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2"><div><label class="fl">A&E Certificate Number <span style="color:var(--red)">*</span></label><input type="text" name="alsCertNo" placeholder="e.g. 123456789" class="fi"></div><div><label class="fl">Test Result/Rating <span style="color:var(--red)">*</span></label><input type="text" name="alsResult" placeholder="e.g. Passed / 90%" class="fi"></div></div></div>
                    <div id="acadReturnee" class="acad-block" style="display:none"><p class="stitle">BTECH Previous Academic Record</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">Last Program Enrolled <span style="color:var(--red)">*</span></label><input type="text" name="returneeProgram" placeholder="e.g. BSIT" class="fi"></div><div><label class="fl">Last Year Attended <span style="color:var(--red)">*</span></label><input type="number" name="returneeYear" placeholder="e.g. 2022" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2"><div><label class="fl">Student ID Number <span style="color:var(--red)">*</span></label><input type="text" name="returneeID" placeholder="e.g. 2020-12345" class="fi"></div><div><label class="fl">Reason for Return <span style="color:var(--red)">*</span></label><input type="text" name="returneeReason" placeholder="e.g. Academic Leave" class="fi"></div></div></div>
          <div id="acadHS" class="acad-block"><p class="stitle">High School</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">School Name <span style="color:var(--red)">*</span></label><input type="text" name="hsSchool" placeholder="e.g. Baliwag High School" class="fi"></div><div><label class="fl">Year Graduated <span style="color:var(--red)">*</span></label><input type="number" name="hsYear" placeholder="e.g. 2020" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div></div>
          <div id="acadSHS" class="acad-block"><p class="stitle">Senior High School</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">School Name <span style="color:var(--red)">*</span></label><input type="text" name="shsSchool" placeholder="e.g. Baliwag Senior High" class="fi"></div><div><label class="fl">Year Graduated <span style="color:var(--red)">*</span></label><input type="number" name="shsYear" placeholder="e.g. 2022" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div></div>
          <div id="acadTertiary" class="acad-block" style="display:none"><p class="stitle">Tertiary (College/University)</p><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="fl">School Name <span style="color:var(--red)">*</span></label><input type="text" name="tertiarySchool" placeholder="e.g. Baliwag Polytechnic College" class="fi"></div><div><label class="fl">Year Graduated <span style="color:var(--red)">*</span></label><input type="number" name="tertiaryYear" placeholder="e.g. 2024" class="fi" min="1980" max="2030" step="1" inputmode="numeric"></div></div></div>
        </div>
      </div>
    </div>

    <!-- STEP 4 -->
    <div class="sc" data-step="4">
      <div class="fcard">
        <div class="fhdr"><h2>Step 4: Contact Information</h2><p>Parent/guardian contact numbers</p></div>
        <div class="fbdy">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="fl">Father's Contact Number <span style="color:var(--red)">*</span></label><div class="phone-wrap"><span class="ppfx">+63</span><input type="tel" name="contactNumber" placeholder="9XXXXXXXXX" class="fi phi" maxlength="10"></div><p style="font-size:11px;color:#9aa5b1;margin-top:4px">10-digit mobile number starting with 9</p><span class="et" id="e-c1"></span></div>
            <div><label class="fl">Mother's Contact Number <span style="color:var(--red)">*</span></label><div class="phone-wrap"><span class="ppfx">+63</span><input type="tel" name="alternateContactNumber" placeholder="9XXXXXXXXX" class="fi phi" maxlength="10"></div><p style="font-size:11px;color:#9aa5b1;margin-top:4px">10-digit mobile number starting with 9</p><span class="et" id="e-c2"></span></div>
          </div>
          <div class="eb" id="e3"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please enter valid contact numbers.</div>
        </div>
      </div>
    </div>

    <!-- STEP 5 -->
    <div class="sc" data-step="5">
      <div class="fcard">
        <div class="fhdr"><h2>Step 5: Permanent Address</h2><p>Your official permanent home address</p></div>
        <div class="fbdy">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="fl">Province <span style="color:var(--red)">*</span></label>
              <select name="permanentProvince" class="fi" id="permanentProvinceSelect">
                <option value="">Select province</option>
              </select>
              <input type="hidden" name="permanentProvincePsgcCode">
              <span class="et" id="e-pp"></span>
            </div>
            <div>
              <label class="fl">Town / City <span style="color:var(--red)">*</span></label>
              <select name="permanentCity" class="fi" id="permanentCitySelect" disabled>
                <option value="">Select town / city</option>
              </select>
              <input type="hidden" name="permanentCityPsgcCode">
              <span class="et" id="e-pc"></span>
            </div>
            <div>
              <label class="fl">Barangay <span style="color:var(--red)">*</span></label>
              <select name="permanentBarangay" class="fi" id="permanentBarangaySelect" disabled>
                <option value="">Select barangay</option>
              </select>
              <input type="hidden" name="permanentBarangayPsgcCode">
              <span class="et" id="e-pb"></span>
            </div>
            <div><label class="fl">ZIP Code <span style="color:var(--red)">*</span></label><input type="text" name="permanentZipCode" placeholder="e.g. 3006" class="fi" maxlength="4"><span class="et" id="e-pz"></span></div>
          </div>
          <p style="font-size:11px;color:#6b7280;margin-top:8px">Location list is loaded from PSGC API.</p>
          <div class="eb" id="e4"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please fill in all required address fields.</div>
        </div>
      </div>
    </div>

    <!-- STEP 6 -->
    <div class="sc" data-step="6">
      <div class="fcard">
        <div class="fhdr"><h2>Step 6: Present Address</h2><p>Where you are currently residing</p></div>
        <div class="fbdy">
          <label class="oc" style="align-items:center;margin-bottom:20px"><input type="checkbox" id="sameChk" style="width:18px;height:18px;accent-color:var(--navy);flex-shrink:0"><span style="margin-left:12px;font-weight:700;color:#1f2937">Same as Permanent Address</span><span style="margin-left:auto;font-size:11px;color:var(--navy-mid);background:#dbeafe;padding:2px 10px;border-radius:999px;font-weight:700">Auto-fill</span></label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="fl">Province <span style="color:var(--red)">*</span></label>
              <select name="presentProvince" class="fi" id="presentProvinceSelect">
                <option value="">Select province</option>
              </select>
              <input type="hidden" name="presentProvincePsgcCode">
              <span class="et" id="e-rp"></span>
            </div>
            <div>
              <label class="fl">Town / City <span style="color:var(--red)">*</span></label>
              <select name="presentCity" class="fi" id="presentCitySelect" disabled>
                <option value="">Select town / city</option>
              </select>
              <input type="hidden" name="presentCityPsgcCode">
              <span class="et" id="e-rc"></span>
            </div>
            <div>
              <label class="fl">Barangay <span style="color:var(--red)">*</span></label>
              <select name="presentBarangay" class="fi" id="presentBarangaySelect" disabled>
                <option value="">Select barangay</option>
              </select>
              <input type="hidden" name="presentBarangayPsgcCode">
              <span class="et" id="e-rb"></span>
            </div>
            <div><label class="fl">ZIP Code <span style="color:var(--red)">*</span></label><input type="text" name="presentZipCode" placeholder="e.g. 3006" class="fi" maxlength="4"><span class="et" id="e-rz"></span></div>
          </div>
          <div class="eb" id="e5"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please fill in all required address fields.</div>
        </div>
      </div>
    </div>

    <!-- STEP 7 -->
    <div class="sc" data-step="7">
      <div class="fcard">
        <div class="fhdr"><h2>Step 7: Degree Course Selection</h2><p>Your 1st and 2nd choices must be different programs</p></div>
        <div class="fbdy">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <p class="stitle" style="border-color:#c9933a">1st Choice <span style="color:var(--red)">*</span></p>
              <div id="firstChoiceList">Loading programs...</div>
              <span class="et" id="e-fc"></span>
            </div>
            <div>
              <p class="stitle" style="border-color:#c9933a">2nd Choice <span style="color:var(--red)">*</span></p>
              <div id="secondChoiceList">Loading programs...</div>
              <span class="et" id="e-sc2"></span>
            </div>
          </div>
          <div class="eb" id="e6"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i><span id="e6t">Please select both 1st and 2nd course choices.</span></div>
        </div>
      </div>
    </div>

    <!-- STEP 8 -->
    <div class="sc" data-step="8">
      <div class="fcard">
        <div class="fhdr"><h2>Step 8: General Weighted Average</h2><p>Enter your GWA as a percentage score (70-100)</p></div>
        <div class="fbdy">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5" style="max-width:480px">
            <div><label class="fl">Grade 11 GWA <span style="color:var(--red)">*</span></label><div style="position:relative"><input type="number" name="grade11GWA" placeholder="e.g. 88.5" class="fi" min="70" max="100" step="0.01" style="padding-right:48px"><span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:600;color:#9aa5b1">/ 100</span></div><span class="et" id="e-g1"></span></div>
            <div><label class="fl">Grade 12 - 1st Semester GWA <span style="color:var(--red)">*</span></label><div style="position:relative"><input type="number" name="grade12GWA" placeholder="e.g. 90.0" class="fi" min="70" max="100" step="0.01" style="padding-right:48px"><span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:11px;font-weight:600;color:#9aa5b1">/ 100</span></div><span class="et" id="e-g2"></span></div>
          </div>
          <div class="gd" id="gwaDsp" style="display:none;max-width:480px">
            <p style="font-size:11px;font-weight:600;color:var(--gray-600);margin-bottom:4px">Computed Average GWA</p>
            <div class="gd-avg" id="avgGWA">-</div>
            <p class="gd-rmk" id="gwaRmk"></p>
          </div>
          <div class="eb" id="e7"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please enter valid GWA values between 70 and 100.</div>
        </div>
      </div>
    </div>

    <!-- STEP 9 -->
    <div class="sc" data-step="9">
      <div class="fcard">
        <div class="fhdr"><h2>Step 9: Eligibility Questions</h2><p>Answer honestly - this helps identify applicable benefits and support</p></div>
        <div class="fbdy" style="display:flex;flex-direction:column;gap:24px">
          <div><label class="fl" style="margin-bottom:10px">Are you a Person with Disability (PWD)? <span style="color:var(--red)">*</span></label><div style="display:flex;gap:10px;max-width:280px"><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="differentlyAbled" value="yes" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Yes</span></label><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="differentlyAbled" value="no" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">No</span></label></div><span class="et" id="e-pwd"></span></div>
          <div><label class="fl" style="margin-bottom:10px">Are you a Solo Parent or child of a Solo Parent? <span style="color:var(--red)">*</span></label><div style="display:flex;gap:10px;max-width:280px"><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="soloParent" value="yes" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Yes</span></label><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="soloParent" value="no" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">No</span></label></div><span class="et" id="e-solo"></span></div>
          <div><label class="fl" style="margin-bottom:10px">Are you an Indigenous person or a Member of Indigenous Tribe? <span style="color:var(--red)">*</span></label><div style="display:flex;gap:10px;max-width:280px"><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="indigenous" value="yes" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Yes</span></label><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="indigenous" value="no" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">No</span></label></div><span class="et" id="e-indigenous"></span></div>
          <div><label class="fl" style="margin-bottom:10px">Are you a Beneficiary of the 4PS Program of the Government? <span style="color:var(--red)">*</span></label><div style="display:flex;gap:10px;max-width:280px"><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="fourPs" value="yes" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">Yes</span></label><label class="oc" style="flex:1;margin-bottom:0;align-items:center"><input type="radio" name="fourPs" value="no" style="width:15px;height:15px;accent-color:var(--navy)"><span style="margin-left:8px;font-size:13px;font-weight:600">No</span></label></div><span class="et" id="e-4ps"></span></div>
          <div class="eb" id="e8"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>Please answer all eligibility questions.</div>
        </div>
      </div>
    </div>

    <!-- STEP 10 -->
    <div class="sc" data-step="10">
      <div class="fcard">
        <div class="fhdr"><h2>Step 10: Declaration &amp; Data Privacy</h2><p>Read and accept all agreements before proceeding</p></div>
        <div class="fbdy">
          <div style="display:flex;flex-direction:column;gap:12px">
            <label class="oc" style="align-items:flex-start"><input type="checkbox" name="declarationTruth" style="width:18px;height:18px;margin-top:2px;accent-color:var(--navy);flex-shrink:0"><span style="margin-left:14px"><span style="font-weight:700;color:#1f2937">I affirm all information provided is true and correct</span><p style="font-size:12px;color:#6b7280;margin-top:3px">False information may result in disqualification and possible legal consequences.</p></span></label>
            <label class="oc" style="align-items:flex-start"><input type="checkbox" name="dataPrivacy" style="width:18px;height:18px;margin-top:2px;accent-color:var(--navy);flex-shrink:0"><span style="margin-left:14px"><span style="font-weight:700;color:#1f2937">I agree to the processing of my personal data</span><p style="font-size:12px;color:#6b7280;margin-top:3px">In accordance with the Data Privacy Act of 2012 (Republic Act 10173).</p></span></label>
            <label class="oc" style="align-items:flex-start"><input type="checkbox" name="authorizedProcessing" style="width:18px;height:18px;margin-top:2px;accent-color:var(--navy);flex-shrink:0"><span style="margin-left:14px"><span style="font-weight:700;color:#1f2937">I authorize BTECH to use my data for admission purposes</span><p style="font-size:12px;color:#6b7280;margin-top:3px">Including communication about my application status and enrollment processes.</p></span></label>
          </div>
          <div class="eb" id="e9"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i>You must agree to all three declarations to continue.</div>
        </div>
      </div>
    </div>

    <!-- STEP 11 -->
    <div class="sc" data-step="11">
      <div class="fcard">
        <div class="fhdr"><h2>Step 11: Upload ID Photo</h2><p>Upload a clear 2x2 ID picture for your application record</p></div>
        <div class="fbdy">
          <p class="stitle">2x2 ID Picture <span style="color:var(--red)">*</span></p>
          <div class="upload-photo-panel">
            <div>
              <div class="pic-zone" id="picZone">
                <input type="file" id="picInput" accept="image/jpeg,image/png,image/jpg" onchange="handlePic(this)">
                <i data-iconsax="image" style="width:30px;height:30px;color:#b5cce4"></i>
                <span style="font-size:11px;color:#b5cce4;font-weight:700;margin-top:6px">Click to upload</span>
              </div>
              <p style="font-size:10px;color:#9aa5b1;margin-top:6px;text-align:center">JPG / PNG only - max 5MB</p>
              <span class="et" id="e-pic"></span>
            </div>
            <div class="photo-note">
              <p style="font-size:13px;font-weight:700;color:var(--navy);margin-bottom:8px">Photo Requirements</p>
              <ul style="font-size:12px;color:#4b5563;display:flex;flex-direction:column;gap:5px;list-style:none;padding:0">
                <li>Taken within the last 6 months</li>
                <li>White or plain light-colored background</li>
                <li>Plain clothing (no uniforms/prints)</li>
                <li>Face clearly visible, no eyeglasses</li>
                <li>File format: JPG or PNG</li>
              </ul>
            </div>
          </div>
          <div class="eb" id="e10"><i data-iconsax="alert-circle" style="width:16px;height:16px"></i><span id="e10t">Please upload your 2x2 ID picture.</span></div>
        </div>
      </div>
    </div>

    <!-- STEP 12 - REVIEW & SUBMIT -->
    <div class="sc" data-step="12">
      <div class="review-wrap">

        <!-- Action Bar -->
        <div class="review-action-bar no-print">
          <div>
            <h3>Application Review</h3>
            <p>Verify all details are correct before final submission</p>
          </div>
          <div class="action-btns">
            <button type="button" class="bprint" onclick="window.print()">
              <i data-iconsax="printer" style="width:14px;height:14px"></i>
              Print / Save PDF
            </button>
            <button type="button" class="bsub" onclick="trySubmit()">
              <i data-iconsax="check" style="width:14px;height:14px"></i>
              Submit Application
            </button>
          </div>
        </div>

        <!-- REVIEW DOCUMENT SHEET -->
        <div class="review-sheet" id="reviewSheet">

          <!-- Letterhead -->
          <div class="rs-header">
            <div class="rs-logo-box">
              <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
              <i data-iconsax="graduation-cap" style="display:none;width:44px;height:44px;color:var(--navy)"></i>
            </div>
            <div class="rs-school-info">
              <div class="rs-republic-line">Republic of the Philippines</div>
              <div class="rs-school-name">BALIWAG POLYTECHNIC COLLEGE</div>
              <div class="rs-subtitle">Dalubhasaang Politekniko ng Lungsod ng Baliwag</div>
              <div class="rs-divider-line"></div>
              <div class="rs-addr">
                Baliwag City, Bulacan, Philippines &nbsp;|&nbsp; Tel: (044) xxx-xxxx &nbsp;|&nbsp; Fax: (044) xxx-xxxx<br>
                Email: admissions@btech.edu.ph &nbsp;|&nbsp; Website: www.btech.edu.ph
              </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:center;gap:4px;flex-shrink:0">
              <div class="rs-pic-box" id="rv-picBox">
                <span class="rs-pic-placeholder">2x2<br>ID Photo</span>
                <div class="rs-pic-label">APPLICANT PHOTO</div>
              </div>
            </div>
          </div>

          <!-- Form Title -->
          <div class="rs-title-band">
            <h3>College Admission Application Form</h3>
          </div>

          <!-- Ref / Date Meta -->
          <div class="rs-form-meta">
            <div class="ref-block">
              <span class="ref-key">Reference No.</span>
              <span class="ref-val" id="rv-ref">--</span>
            </div>
            <div class="instr">Verify all information before submission. Misrepresentation may result in disqualification.</div>
            <div class="ref-block" style="text-align:right">
              <span class="ref-key">Date Filed</span>
              <span class="ref-val" id="rv-date">--</span>
            </div>
          </div>

          <!-- Section I -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section I - Applicant's Personal Information <span class="rs-sec-num">As it appears on official government ID</span></div>
            <table class="rs-table">
              <tr><td class="lbl">Family Name</td><td class="val" id="rv-surname"></td><td class="lbl" style="width:120px">First Name</td><td class="val" id="rv-firstName"></td></tr>
              <tr><td class="lbl">Middle Name</td><td class="val" id="rv-middleName"></td><td class="lbl">Suffix</td><td class="val" id="rv-suffix"></td></tr>
              <tr><td class="lbl">Date of Birth</td><td class="val" id="rv-dob"></td><td class="lbl">Place of Birth</td><td class="val" id="rv-pob"></td></tr>
              <tr><td class="lbl">Sex</td><td class="val" colspan="3"><span id="rv-male" class="sex-check">&#9744; Male</span><span id="rv-female" class="sex-check">&#9744; Female</span></td></tr>
              <tr><td class="lbl">Permanent Address</td><td class="val" colspan="3" id="rv-permAddr"></td></tr>
              <tr><td class="lbl">Present Address</td><td class="val" colspan="3" id="rv-presAddr"></td></tr>
            </table>
          </div>

          <!-- Section II -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section II - Family &amp; Contact Information</div>
            <table class="rs-table">
              <tr><td class="lbl">Mother's Full Name</td><td class="val" id="rv-motherName"></td><td class="lbl" style="width:130px">Mother's Contact</td><td class="val" id="rv-mContact"></td></tr>
              <tr><td class="lbl">Father's Full Name</td><td class="val" id="rv-fatherName"></td><td class="lbl">Father's Contact</td><td class="val" id="rv-fContact"></td></tr>
            </table>
          </div>

          <!-- Section III -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section III - Academic Details &amp; Course Preference</div>
            <table class="rs-table">
              <tr><td class="lbl">1st Course Choice</td><td class="val" id="rv-course1"></td><td class="lbl" style="width:160px">2nd Course Choice</td><td class="val" id="rv-course2"></td></tr>
              <tr><td class="lbl">Grade 11 GWA</td><td class="val" id="rv-g11"></td><td class="lbl">Grade 12 GWA (1st Sem)</td><td class="val" id="rv-g12"></td></tr>
              <tr><td class="lbl">Average GWA</td><td class="val" id="rv-gwa" style="font-weight:700"></td><td class="lbl">Academic Standing</td><td class="val" id="rv-gwarmk"></td></tr>
            </table>
          </div>

          <!-- Section IV -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section IV - Applicant Classification</div>
            <table class="rs-table">
              <tr><td class="lbl">Applicant Type</td><td class="val" id="rv-type"></td><td class="lbl" style="width:200px">Person with Disability (PWD)</td><td class="val" id="rv-pwd"></td></tr>
              <tr><td class="lbl">Solo Parent / Child of Solo Parent</td><td class="val" id="rv-solo"></td><td class="lbl">4Ps Beneficiary</td><td class="val" id="rv-4ps"></td></tr>
              <tr><td class="lbl">Indigenous Person / Tribe Member</td><td class="val" id="rv-indigenous" colspan="3"></td></tr>
            </table>
          </div>

          <!-- Section V -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section V - Uploaded Photo</div>
            <div class="rs-docs-grid" id="rv-docs"></div>
          </div>

          <!-- Section VI -->
          <div class="rs-section">
            <div class="rs-sec-hdr">Section VI - Applicant's Declaration</div>
            <div class="rs-declaration">
              I hereby declare that all information provided in this application form is true, complete, and correct to the best of my knowledge and belief.
              I consent to the processing of my personal data in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173),
              and I authorize the Baliwag Polytechnic College to collect, use, and process my information for admission, enrollment, and related institutional purposes.
              I understand that any misrepresentation or falsification of information shall be sufficient ground for the cancellation of my admission or dismissal from the institution.
            </div>
            <div class="rs-sig-row">
              <div class="rs-sig-cell"><div class="rs-sig-line"></div><div class="rs-sig-lbl">Signature over Printed Name of Applicant</div></div>
              <div class="rs-sig-cell"><div class="rs-sig-line"></div><div class="rs-sig-lbl">Date Signed</div></div>
              <div class="rs-sig-cell"><div class="rs-sig-line"></div><div class="rs-sig-lbl">Signature of Parent / Guardian</div></div>
            </div>
          </div>

          <!-- Official Use Only -->
          <div class="rs-official-use">
            <div class="rs-official-use-hdr">For Official Use Only</div>
            <div class="rs-official-cells">
              <div class="rs-official-cell"><div class="rs-official-cell-lbl">Received by</div></div>
              <div class="rs-official-cell"><div class="rs-official-cell-lbl">Verified by</div></div>
              <div class="rs-official-cell"><div class="rs-official-cell-lbl">Approved by</div></div>
              <div class="rs-official-cell"><div class="rs-official-cell-lbl">Remarks</div></div>
            </div>
          </div>

          <!-- Footer -->
          <div class="rs-footer">
            <span class="rs-footer-left">BTECH - Office of Admissions &nbsp;|&nbsp; For Official Use Only</span>
            <span class="rs-footer-right">Ref: <span id="rv-ref2"></span></span>
          </div>

          <div class="rs-corner-bl"></div>
          <div class="rs-corner-br"></div>

        </div><!-- /review-sheet -->
      </div><!-- /review-wrap -->
    </div>

    <!-- Nav Bar -->
    <div class="nav-bar no-print" id="navBar">
      <button type="button" id="prevBtn" class="bs" onclick="nav(-1)">
        <i data-iconsax="chevron-left" style="width:15px;height:15px"></i>
        Previous
      </button>
      <span class="nav-hint hidden md:block" id="stepHint">Select an option to continue</span>
      <button type="button" id="nextBtn" class="bp" onclick="nav(1)">
        Next
        <i data-iconsax="chevron-right" style="width:15px;height:15px"></i>
      </button>
    </div>

  </form>
</main>

<!-- CONFIRMATION MODAL -->
<div class="mo" id="moConfirm">
  <div class="mb">
    <div style="text-align:center">
      <div style="width:56px;height:56px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <i data-iconsax="check-circle" style="width:26px;height:26px;color:var(--navy)"></i>
      </div>
      <h3 style="font-family:'DM Serif Display',serif;font-size:20px;font-weight:400;color:#1f2937;margin-bottom:6px">Ready to Submit?</h3>
      <p style="font-size:13px;color:#6b7280;margin-bottom:20px;line-height:1.5">This action cannot be undone. Your application will be forwarded to the BTECH Admissions Office for processing.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <button class="bs" style="justify-content:center" onclick="closeModal('moConfirm')"><i data-iconsax="x" style="width:13px;height:13px"></i>Cancel</button>
        <button class="bsub" style="justify-content:center" id="submitBtn" onclick="doSubmit()"><i data-iconsax="check" style="width:13px;height:13px"></i>Confirm &amp; Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- SUCCESS MODAL -->
<div class="mo" id="moSuccess">
  <div class="mb" style="text-align:center">
    <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 16px"><i data-iconsax="check" style="width:30px;height:30px;color:var(--sage)"></i></div>
    <h3 style="font-family:'DM Serif Display',serif;font-size:22px;font-weight:400;color:#1f2937;margin-bottom:6px">Application Submitted!</h3>
    <p style="font-size:13px;color:#6b7280;margin-bottom:14px">Your admission application has been successfully submitted.</p>
    <div style="background:#f0f7ff;border-radius:12px;padding:14px 18px;margin-bottom:14px;border:1.5px solid #c3dafe">
      <p style="font-size:11px;color:#9aa5b1;margin-bottom:4px;font-weight:600">Your Reference Number</p>
      <p style="font-family:'DM Serif Display',serif;font-size:22px;color:var(--navy);letter-spacing:2px" id="refNum">--</p>
    </div>
    <p style="font-size:11px;color:#9aa5b1;margin-bottom:20px;line-height:1.6">Please save your reference number. The admissions office will contact you regarding the next steps.</p>
    <button class="bp" style="width:100%;justify-content:center" data-home-url="{{ route('home') }}" onclick="window.location.href = this.dataset.homeUrl"><i data-iconsax="home" style="width:15px;height:15px"></i>Return to Home</button>
  </div>
</div>

<!-- REQUIREMENTS MODAL -->
<div class="mo" id="moReq">
  <div class="mb" style="max-width:640px;padding:32px 28px 24px 28px;">
    <h2 id="moReqTitle" style="font-size:22px;font-weight:700;color:#254d82;margin-bottom:18px;text-align:center;"></h2>
    <ul id="moReqList" style="font-size:15px;color:#1b3557;margin-bottom:22px;list-style:disc inside;padding-left:10px;line-height:2;"></ul>
    <button class="bp" style="width:100%;margin-top:8px;justify-content:center;" onclick="closeModal('moReq')">Close</button>
  </div>
</div>

<!-- API base is auto-detected by api-config.js (e.g. /admission-office/api on XAMPP). -->
<script src="{{ asset('js/api-config.js') }}?v=9"></script>
<script src="{{ asset('js/admission-api.js') }}?v=13"></script>
<script src="{{ asset('js/form.js') }}?v=10"></script>
<script>
  if (typeof iconsax !== 'undefined') {
    iconsax.createIcons();
  }

  // Hide site loader
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
</script>
</body>
</html>



