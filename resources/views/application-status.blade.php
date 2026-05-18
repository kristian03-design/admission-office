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
  <link rel="stylesheet" href="{{ asset('css/applicant-portal.css') }}?v=3" />
</head>

<body>
  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="74" height="74" decoding="async" onerror="this.remove()"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSIONS OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Dalubhasaang Politekniko ng Lungsod ng Baliwag</p>
        </div>
      </a>
      <nav class="nav-desktop hidden md:flex items-center gap-6">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="nav-actions flex items-center gap-3">
        <a href="{{ route('apply') }}?fresh=true" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
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
        <div class="mobile-menu-footer" style="--i:6">
          <a href="{{ route('apply') }}?fresh=true" class="mobile-btn-primary">
            <span>Inquire Now</span>
            <i data-iconsax="arrow-right"></i>
          </a>
        </div>
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
              <button id="sendOtpBtn" class="portal-btn portal-btn-primary w-full" type="submit" data-idle-text="Send OTP" data-loading-text="Sending OTP...">Send OTP</button>
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
              <input class="portal-input text-center text-2xl tracking-[.35em] font-bold" name="otp" maxlength="6" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" required>
              <button id="verifyOtpBtn" class="portal-btn portal-btn-primary w-full" type="submit" data-idle-text="Verify and Continue" data-loading-text="Verifying...">Verify and Continue</button>
            </form>
            <button id="resendOtp" class="portal-btn portal-btn-ghost w-full mt-3" type="button" data-idle-text="Resend OTP" data-loading-text="Resending OTP...">Resend OTP</button>
          </div>

        </div>
      </div>
    </section>

    <!-- ── Help band ── -->
    <section class="portal-help-band">
      <div class="max-w-7xl mx-auto px-8 portal-help-grid">
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="receipt-search"></i></span>
          <div>
            <p class="font-bold">Reference number</p>
            <p>Use the number shown after submitting your application.</p>
          </div>
        </div>
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="sms"></i></span>
          <div>
            <p class="font-bold">Submitted email</p>
            <p>The OTP is sent to the email used on your form.</p>
          </div>
        </div>
        <div class="portal-help-item">
          <span class="portal-help-icon"><i data-iconsax="edit-2"></i></span>
          <div>
            <p class="font-bold">Editable while pending</p>
            <p>Changes are locked once active review begins.</p>
          </div>
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
  <div id="portalToast" class="toast"></div>

  <script src="{{ asset('js/api-config.js') }}?v=4"></script>
  <script src="{{ asset('js/applicant-portal.js') }}?v=4"></script>
</body>

</html>

