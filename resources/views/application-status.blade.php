<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Applicant Portal - BTECH Admissions</title>
  <script>
    window.ICONSAX_SPRITE_PATH = "{{ asset('assets/iconsax-sprite.svg') }}";
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=33" />
  <style>
    :root { --portal-navy: #0f1e3d; --portal-gold: #d97706; --portal-muted: #64748b; --portal-line: #e2e8f0; }
    body { font-family: "DM Sans", sans-serif; background: #f8fafc; color: #0f172a; }
    .portal-shell { min-height: 100vh; padding-top: 96px; }
    .portal-hero { background: linear-gradient(135deg, #0f1e3d 0%, #172d56 55%, #24406e 100%); color: #fff; }
    .portal-hero h1 { font-family: "Cormorant Garamond", serif; letter-spacing: 0; }
    .portal-card { background: #fff; border: 1px solid var(--portal-line); border-radius: 8px; box-shadow: 0 18px 45px rgba(15, 23, 42, .08); }
    .portal-input, .portal-select, .portal-textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 11px 12px; font-size: 14px; background: #fff; color: #0f172a; min-height: 44px; }
    .portal-textarea { min-height: 92px; resize: vertical; }
    .portal-input:focus, .portal-select:focus, .portal-textarea:focus { outline: 2px solid rgba(217, 119, 6, .2); border-color: var(--portal-gold); }
    .portal-btn { min-height: 44px; border-radius: 8px; padding: 10px 16px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: .18s ease; }
    .portal-btn-primary { background: var(--portal-navy); color: #fff; }
    .portal-btn-primary:hover { background: #172d56; transform: translateY(-1px); }
    .portal-btn-ghost { border: 1px solid var(--portal-line); color: var(--portal-navy); background: #fff; }
    .portal-btn-ghost:hover { border-color: #94a3b8; }
    .portal-btn:disabled { opacity: .55; cursor: not-allowed; transform: none; }
    .portal-step { display: none; }
    .portal-step.active { display: block; }
    .portal-tabs { display: flex; gap: 8px; overflow-x: auto; border-bottom: 1px solid var(--portal-line); }
    .portal-tab { padding: 14px 12px; white-space: nowrap; font-size: 13px; font-weight: 700; color: #64748b; border-bottom: 2px solid transparent; }
    .portal-tab.active { color: var(--portal-navy); border-color: var(--portal-gold); }
    .portal-panel { display: none; }
    .portal-panel.active { display: block; }
    .status-badge { border-radius: 999px; padding: 7px 12px; font-size: 12px; font-weight: 800; display: inline-flex; align-items: center; gap: 6px; }
    .status-pending, .status-submitted, .status-pending_docs { background: #fffbeb; color: #b45309; }
    .status-under_review, .status-for_interview { background: #eff6ff; color: #1d4ed8; }
    .status-approved, .status-accepted, .status-enrolled { background: #ecfdf5; color: #047857; }
    .status-rejected, .status-cancelled { background: #fef2f2; color: #b91c1c; }
    .timeline { display: grid; gap: 12px; }
    .timeline-item { display: grid; grid-template-columns: 34px 1fr; gap: 12px; align-items: start; }
    .timeline-dot { width: 28px; height: 28px; border-radius: 999px; display: grid; place-items: center; border: 1px solid #cbd5e1; background: #fff; color: #94a3b8; }
    .timeline-item.done .timeline-dot { background: var(--portal-navy); border-color: var(--portal-navy); color: #fff; }
    .timeline-item.current .timeline-dot { background: #fffbeb; border-color: #f59e0b; color: #d97706; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .detail-field label { display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
    .doc-row { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center; padding: 14px; border: 1px solid var(--portal-line); border-radius: 8px; }
    .doc-row.missing { border-color: #fcd34d; background: #fffbeb; }
    .portal-alert { border-radius: 8px; padding: 12px 14px; font-size: 13px; line-height: 1.6; }
    .portal-alert-warn { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .portal-alert-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .portal-alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .portal-toast { position: fixed; right: 18px; bottom: 18px; z-index: 80; max-width: 360px; border-radius: 8px; padding: 13px 15px; background: #0f1e3d; color: #fff; box-shadow: 0 18px 45px rgba(15,23,42,.22); transform: translateY(18px); opacity: 0; pointer-events: none; transition: .2s ease; }
    .portal-toast.show { transform: translateY(0); opacity: 1; }
    @media (max-width: 768px) {
      .portal-shell { padding-top: 78px; }
      .detail-grid { grid-template-columns: 1fr; }
      .portal-hero .portal-hero-grid { grid-template-columns: 1fr; }
      .doc-row { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-scrolled">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="74" height="74"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSIONS OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Applicant Portal</p>
        </div>
      </a>
      <nav class="hidden md:flex items-center gap-6">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('apply') }}" class="nav-link text-sm font-medium tracking-wide">Apply</a>
        <a href="{{ route('faqs') }}" class="nav-link text-sm font-medium tracking-wide">FAQs</a>
      </nav>
    </div>
  </header>

  <main class="portal-shell">
    <section class="portal-hero">
      <div class="max-w-7xl mx-auto px-8 py-16 grid md:grid-cols-[1.15fr_.85fr] gap-8 portal-hero-grid items-center">
        <div>
          <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-200 mb-3">BTECH Admissions</p>
          <h1 class="text-5xl md:text-6xl font-semibold leading-tight">Applicant Portal</h1>
          <p class="mt-4 text-white/75 max-w-2xl leading-relaxed">Check your application status, update allowed details, upload missing documents, and review your interview schedule using your reference number and submitted email.</p>
        </div>
        <div class="portal-card p-6 text-slate-900">
          <div id="lookupStep" class="portal-step active">
            <h2 class="text-xl font-bold text-[#0f1e3d]">Open your application</h2>
            <p class="text-sm text-slate-500 mt-1">Enter the details from your submitted application.</p>
            <form id="lookupForm" class="mt-5 space-y-4">
              <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Reference Number</label>
                <input class="portal-input mt-1" name="reference_number" placeholder="BTECH-2026-000123" required>
              </div>
              <div>
                <label class="text-xs font-bold text-slate-500 uppercase">Submitted Email</label>
                <input class="portal-input mt-1" type="email" name="email" placeholder="you@example.com" required>
              </div>
              <button class="portal-btn portal-btn-primary w-full" type="submit">Send OTP</button>
            </form>
          </div>

          <div id="otpStep" class="portal-step">
            <button id="backToLookup" class="text-sm font-bold text-slate-500 mb-4" type="button">&larr; Back</button>
            <h2 class="text-xl font-bold text-[#0f1e3d]">Verify your email</h2>
            <p class="text-sm text-slate-500 mt-1">Enter the 6-digit code sent to your application email.</p>
            <form id="otpForm" class="mt-5 space-y-4">
              <input class="portal-input text-center text-2xl tracking-[.35em] font-bold" name="otp" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required>
              <button class="portal-btn portal-btn-primary w-full" type="submit">Verify and Continue</button>
            </form>
            <button id="resendOtp" class="portal-btn portal-btn-ghost w-full mt-3" type="button">Resend OTP</button>
          </div>
        </div>
      </div>
    </section>

    <section id="dashboardStep" class="portal-step max-w-7xl mx-auto px-8 py-10">
      <div class="portal-card p-5 md:p-7 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <p class="text-sm text-slate-500">Welcome back</p>
            <h2 id="applicantName" class="text-3xl font-bold text-[#0f1e3d]"></h2>
            <p class="text-sm text-slate-500 mt-1">Reference: <span id="applicantReference" class="font-bold text-slate-700"></span></p>
          </div>
          <div class="flex flex-wrap items-center gap-2">
            <span id="statusBadge" class="status-badge"></span>
            <span id="lastUpdated" class="text-xs font-semibold text-slate-500"></span>
          </div>
        </div>
      </div>

      <div id="lockedNotice" class="portal-alert portal-alert-info mb-6 hidden">Your application is currently under review. Editing is locked while the admissions office processes your application.</div>
      <div id="pendingDocsNotice" class="portal-alert portal-alert-warn mb-6 hidden">Please review your document checklist and upload any missing requirements.</div>

      <div class="portal-card">
        <div class="portal-tabs px-4" id="portalTabs">
          <button class="portal-tab active" data-tab="status" type="button">Status</button>
          <button class="portal-tab" data-tab="details" type="button">Application Details</button>
          <button class="portal-tab" data-tab="documents" type="button">Documents</button>
          <button class="portal-tab" data-tab="interview" type="button">Interview</button>
        </div>

        <div class="p-5 md:p-7">
          <section id="panel-status" class="portal-panel active">
            <h3 class="text-xl font-bold text-[#0f1e3d] mb-4">Application Timeline</h3>
            <div id="statusTimeline" class="timeline"></div>
          </section>

          <section id="panel-details" class="portal-panel">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
              <div>
                <h3 class="text-xl font-bold text-[#0f1e3d]">Application Details</h3>
                <p class="text-sm text-slate-500">You can edit while your application is still open for changes.</p>
              </div>
              <div class="flex gap-2">
                <button id="editDetailsBtn" class="portal-btn portal-btn-ghost" type="button">Edit</button>
                <button id="saveDetailsBtn" class="portal-btn portal-btn-primary hidden" type="button">Save Changes</button>
                <button id="cancelEditBtn" class="portal-btn portal-btn-ghost hidden" type="button">Cancel</button>
              </div>
            </div>
            <div id="choiceWarning" class="portal-alert portal-alert-warn mb-5 hidden">Changing your first choice may affect your admission processing. Please review carefully before submitting.</div>
            <form id="detailsForm" class="space-y-7"></form>
          </section>

          <section id="panel-documents" class="portal-panel">
            <h3 class="text-xl font-bold text-[#0f1e3d] mb-1">Documents</h3>
            <p class="text-sm text-slate-500 mb-5">Upload or replace documents while your application is editable.</p>
            <div id="documentList" class="grid gap-3"></div>
          </section>

          <section id="panel-interview" class="portal-panel">
            <h3 class="text-xl font-bold text-[#0f1e3d] mb-4">Interview Schedule</h3>
            <div id="interviewCard"></div>
          </section>
        </div>
      </div>
    </section>
  </main>

  @include('partials.footer')
  <div id="portalToast" class="portal-toast"></div>

  <script src="{{ asset('js/api-config.js') }}?v=4"></script>
  <script src="{{ asset('js/applicant-portal.js') }}?v=1"></script>
</body>

</html>
