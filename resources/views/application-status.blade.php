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
    :root {
      --portal-navy: #0f1e3d;
      --portal-gold: #d97706;
      --portal-muted: #64748b;
      --portal-line: #e2e8f0;
    }

    body {
      font-family: "DM Sans", sans-serif;
      background: #f7f9fc;
      color: #0f172a;
    }

    /* ── Shell ── */
    .portal-shell {
      padding-top: 84px;
      background: #f7f9fc;
    }

    /* ── Header ── */
    .portal-header {
      background: rgba(15, 30, 61, .96);
      border-bottom: 1px solid rgba(255, 255, 255, .12);
      box-shadow: 0 12px 36px rgba(15, 23, 42, .18);
    }

    .portal-header .nav-inner {
      padding-top: 12px;
      padding-bottom: 12px;
    }

    .portal-header .logo-badge {
      width: 54px;
      height: 54px;
      min-width: 54px;
      box-shadow: 0 8px 26px rgba(245, 158, 11, .26);
    }

    .portal-header .logo-badge img {
      width: 54px;
      height: 54px;
      object-fit: contain;
    }

    .portal-header .nav-sub {
      color: rgba(255, 255, 255, .58);
    }

    .portal-header .nav-main,
    .portal-header .nav-link {
      color: #fff;
    }

    .portal-header .nav-link:hover {
      color: #fcd34d;
    }

    /* ── Hero ── */
    .portal-hero {
      position: relative;
      overflow: visible;
      /* FIX: was hidden, now allows card to breathe */
      background: radial-gradient(circle at 18% 18%, rgba(245, 158, 11, .18), transparent 30%),
        linear-gradient(135deg, #0f1e3d 0%, #182f5a 58%, #28466f 100%);
      color: #fff;
    }

    .portal-hero::after {
      content: "";
      position: absolute;
      inset: auto 0 0 0;
      height: 1px;
      background: rgba(255, 255, 255, .12);
    }

    /* FIX: was min-height 430px with no padding — card bled above the section.
       Now uses padding to contain the card fully within the hero band. */
    .portal-hero-grid {
      min-height: 0;
      padding-top: 56px;
      padding-bottom: 64px;
      align-items: center;
      /* FIX: vertically center both columns */
    }

    .portal-hero h1 {
      font-family: "Cormorant Garamond", serif;
      letter-spacing: 0;
    }

    /* ── Cards ── */
    .portal-card {
      background: #fff;
      border: 1px solid var(--portal-line);
      border-radius: 8px;
      box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
    }

    .portal-login-card {
      max-width: 520px;
      margin-left: auto;
      border-color: rgba(226, 232, 240, .92);
      box-shadow: 0 24px 70px rgba(15, 23, 42, .22);
      /* FIX: ensure card aligns center in the grid row */
      align-self: center;
    }

    .portal-login-mark {
      width: 42px;
      height: 42px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      color: #92400e;
      background: #fffbeb;
      border: 1px solid #fde68a;
      flex-shrink: 0;
    }

    /* ── Inputs ── */
    .portal-input,
    .portal-select,
    .portal-textarea {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 11px 12px;
      font-size: 14px;
      background: #fff;
      color: #0f172a;
      min-height: 44px;
    }

    .portal-textarea {
      min-height: 92px;
      resize: vertical;
    }

    .portal-input:focus,
    .portal-select:focus,
    .portal-textarea:focus {
      outline: 2px solid rgba(217, 119, 6, .2);
      border-color: var(--portal-gold);
    }

    /* ── Buttons ── */
    .portal-btn {
      min-height: 44px;
      border-radius: 8px;
      padding: 10px 16px;
      font-size: 14px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: .18s ease;
      cursor: pointer;
    }

    .portal-btn-primary {
      background: var(--portal-navy);
      color: #fff;
    }

    .portal-btn-primary:hover {
      background: #172d56;
      transform: translateY(-1px);
    }

    .portal-btn-ghost {
      border: 1px solid var(--portal-line);
      color: var(--portal-navy);
      background: #fff;
    }

    .portal-btn-ghost:hover {
      border-color: #94a3b8;
    }

    .portal-btn:disabled {
      opacity: .55;
      cursor: not-allowed;
      transform: none;
    }

    /* ── Portal steps ── */
    .portal-step {
      display: none;
    }

    .portal-step.active {
      display: block;
    }

    /* ── Tabs ── */
    .portal-tabs {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      border-bottom: 1px solid var(--portal-line);
    }

    .portal-tab {
      padding: 14px 12px;
      white-space: nowrap;
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
      border-bottom: 2px solid transparent;
      cursor: pointer;
      background: none;
      border-top: none;
      border-left: none;
      border-right: none;
    }

    .portal-tab.active {
      color: var(--portal-navy);
      border-color: var(--portal-gold);
    }

    .portal-panel {
      display: none;
    }

    .portal-panel.active {
      display: block;
    }

    /* ── Status badges ── */
    .status-badge {
      border-radius: 999px;
      padding: 7px 12px;
      font-size: 12px;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .status-pending,
    .status-submitted,
    .status-pending_docs {
      background: #fffbeb;
      color: #b45309;
    }

    .status-under_review,
    .status-for_interview {
      background: #eff6ff;
      color: #1d4ed8;
    }

    .status-approved,
    .status-accepted,
    .status-enrolled {
      background: #ecfdf5;
      color: #047857;
    }

    .status-rejected,
    .status-cancelled {
      background: #fef2f2;
      color: #b91c1c;
    }

    /* ── Timeline ── */
    .timeline {
      display: grid;
      gap: 12px;
    }

    .timeline-item {
      display: grid;
      grid-template-columns: 34px 1fr;
      gap: 12px;
      align-items: start;
    }

    .timeline-dot {
      width: 28px;
      height: 28px;
      border-radius: 999px;
      display: grid;
      place-items: center;
      border: 1px solid #cbd5e1;
      background: #fff;
      color: #94a3b8;
    }

    .timeline-item.done .timeline-dot {
      background: var(--portal-navy);
      border-color: var(--portal-navy);
      color: #fff;
    }

    .timeline-item.current .timeline-dot {
      background: #fffbeb;
      border-color: #f59e0b;
      color: #d97706;
    }

    /* ── Detail grid ── */
    .detail-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
    }

    .detail-field label {
      display: block;
      font-size: 11px;
      font-weight: 800;
      color: #64748b;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: .04em;
    }

    /* ── Doc rows ── */
    .doc-row {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 12px;
      align-items: center;
      padding: 14px;
      border: 1px solid var(--portal-line);
      border-radius: 8px;
    }

    .doc-row.missing {
      border-color: #fcd34d;
      background: #fffbeb;
    }

    /* ── Alerts ── */
    .portal-alert {
      border-radius: 8px;
      padding: 12px 14px;
      font-size: 13px;
      line-height: 1.6;
    }

    .portal-alert-warn {
      background: #fffbeb;
      color: #92400e;
      border: 1px solid #fde68a;
    }

    .portal-alert-info {
      background: #eff6ff;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }

    .portal-alert-error {
      background: #fef2f2;
      color: #b91c1c;
      border: 1px solid #fecaca;
    }

    /* ── Toast ── */
    .portal-toast {
      position: fixed;
      right: 18px;
      bottom: 18px;
      z-index: 80;
      max-width: 360px;
      border-radius: 8px;
      padding: 13px 15px;
      background: #0f1e3d;
      color: #fff;
      box-shadow: 0 18px 45px rgba(15, 23, 42, .22);
      transform: translateY(18px);
      opacity: 0;
      pointer-events: none;
      transition: .2s ease;
    }

    .portal-toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    /* ── Help band ── */
    .portal-help-band {
      background: #f7f9fc;
      border-bottom: 1px solid #e2e8f0;
    }

    /* FIX: was missing align-items: stretch — cards had uneven height */
    .portal-help-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
      align-items: stretch;
      /* FIX: equal-height cards */
    }

    /* FIX: added flex layout so cards fill equal height properly */
    .portal-help-item {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 18px;
      min-height: 118px;
      display: flex;
      /* FIX */
      flex-direction: column;
      /* FIX */
    }

    .portal-help-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      background: #eff6ff;
      color: #1d4ed8;
      margin-bottom: 12px;
      flex-shrink: 0;
      /* FIX: don't squish icon */
    }

    .portal-help-item:nth-child(2) .portal-help-icon {
      background: #fffbeb;
      color: #d97706;
    }

    .portal-help-item:nth-child(3) .portal-help-icon {
      background: #ecfdf5;
      color: #047857;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .portal-shell {
        padding-top: 76px;
      }

      .portal-header .nav-inner {
        padding-left: 18px;
        padding-right: 18px;
      }

      .portal-header .logo-badge,
      .portal-header .logo-badge img {
        width: 46px;
        height: 46px;
        min-width: 46px;
      }

      /* FIX: on mobile, restore sensible vertical padding (no min-height needed) */
      .portal-hero-grid {
        padding-top: 34px;
        padding-bottom: 40px;
      }

      .portal-login-card {
        max-width: none;
        margin-left: 0;
      }

      .detail-grid {
        grid-template-columns: 1fr;
      }

      .portal-hero .portal-hero-grid {
        grid-template-columns: 1fr;
      }

      .doc-row {
        grid-template-columns: 1fr;
      }

      .portal-help-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <header id="navbar" class="portal-header fixed top-0 left-0 right-0 z-50">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="74" height="74"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSIONS OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Applicant Portal</p>
        </div>
      </a>
      <nav class="nav-desktop hidden md:flex items-center gap-6">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <button id="menu-toggle" class="md:hidden p-2 rounded-lg" aria-label="Toggle menu" aria-expanded="false">
        <span class="hamburger-icon" aria-hidden="true"></span>
      </button>
    </div>
    </div>
    <div id="mobile-menu" class="mobile-menu md:hidden">
      <div class="mobile-menu-inner">
        <nav class="mobile-nav">
          <a href="{{ route('home') }}" class="mobile-nav-link" style="--i:1">
            <i data-iconsax="home"></i>
            <span>Home</span>
          </a>
          <a href="{{ route('about') }}" class="mobile-nav-link" style="--i:2">
            <i data-iconsax="info-circle"></i>
            <span>About</span>
          </a>
          <a href="{{ route('home') }}#programs" class="mobile-nav-link" style="--i:3">
            <i data-iconsax="book"></i>
            <span>Programs</span>
          </a>
          <a href="{{ route('news-events') }}" class="mobile-nav-link" style="--i:4">
            <i data-iconsax="notification"></i>
            <span>News & Events</span>
          </a>
          <a href="{{ route('home') }}#contact" class="mobile-nav-link" style="--i:5">
            <i data-iconsax="message"></i>
            <span>Contact Us</span>
          </a>
        </nav>

      </div>
    </div>
  </header>

  <main class="portal-shell">

    <!-- ── Hero ── -->
    <section class="portal-hero">
      <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-[1.05fr_.95fr] gap-10 portal-hero-grid">

        <!-- Left: copy -->
        <div class="flex flex-col justify-center">
          <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-200 mb-3">BTECH Admissions</p>
          <h1 class="text-5xl md:text-6xl font-semibold leading-tight">Applicant Portal</h1>
          <p class="mt-4 text-white/80 max-w-2xl leading-relaxed">Check your status, update allowed details, upload missing documents, and review your interview schedule using your reference number and submitted email.</p>
        </div>

        <!-- Right: login card — self-center keeps it vertically centered in the grid -->
        <div class="portal-card portal-login-card p-6 text-slate-900 self-center">

          <!-- Step 1: Lookup -->
          <div id="lookupStep" class="portal-step active">
            <div class="flex items-start gap-3">
              <span class="portal-login-mark"><i data-iconsax="document-text"></i></span>
              <div>
                <h2 class="text-xl font-bold text-[#0f1e3d]">Open your application</h2>
                <p class="text-sm text-slate-500 mt-1">Enter the details from your submitted application.</p>
              </div>
            </div>
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

          <!-- Step 2: OTP -->
          <div id="otpStep" class="portal-step">
            <button id="backToLookup" class="text-sm font-bold text-slate-500 mb-4" type="button">&larr; Back</button>
            <div class="flex items-start gap-3">
              <span class="portal-login-mark"><i data-iconsax="shield-tick"></i></span>
              <div>
                <h2 class="text-xl font-bold text-[#0f1e3d]">Verify your email</h2>
                <p class="text-sm text-slate-500 mt-1">Enter the 6-digit code sent to your application email.</p>
              </div>
            </div>
            <form id="otpForm" class="mt-5 space-y-4">
              <input class="portal-input text-center text-2xl tracking-[.35em] font-bold" name="otp" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required>
              <button class="portal-btn portal-btn-primary w-full" type="submit">Verify and Continue</button>
            </form>
            <button id="resendOtp" class="portal-btn portal-btn-ghost w-full mt-3" type="button">Resend OTP</button>
          </div>

        </div>
      </div>
    </section>

    <!-- ── Help band ── -->
    <section class="portal-help-band">
      <div class="max-w-7xl mx-auto px-8 py-6 portal-help-grid">
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="receipt-search"></i></span>
          <p class="font-bold text-[#0f1e3d]">Reference number</p>
          <p class="text-sm text-slate-500 mt-1">Use the number shown after submitting your application.</p>
        </div>
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="sms"></i></span>
          <p class="font-bold text-[#0f1e3d]">Submitted email</p>
          <p class="text-sm text-slate-500 mt-1">The OTP is sent to the email used on your form.</p>
        </div>
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="edit-2"></i></span>
          <p class="font-bold text-[#0f1e3d]">Editable while pending</p>
          <p class="text-sm text-slate-500 mt-1">Changes are locked once active review begins.</p>
        </div>
      </div>
    </section>

    <!-- ── Dashboard ── -->
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