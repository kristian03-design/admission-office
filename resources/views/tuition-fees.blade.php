<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tuition & Fees — BTECH Admission Office</title>
  <meta name="description" content="View the tuition and fees information for Baliwag Polytechnic College programs.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=16" />
  <style>
    #navbar:not(.scrolled) {
      background: rgba(27, 53, 87, 0.98) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 2px 24px rgba(0,0,0,.18);
    }
    #navbar:not(.scrolled) .nav-sub   { color: rgba(255,255,255,.7)  !important; }
    #navbar:not(.scrolled) .nav-main  { color: #ffffff !important; }
    #navbar:not(.scrolled) .nav-link  { color: rgba(255,255,255,.75) !important; }
    #navbar:not(.scrolled) .nav-link:hover { color: #ffffff !important; }
    #navbar:not(.scrolled) #menu-toggle { color: #ffffff; }

    .fee-card {
      background: #fff;
      border: 1px solid rgba(148,163,184,.25);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(15,30,61,.06);
    }
    .fee-card-header {
      background: linear-gradient(135deg, #1b3557, #2a5298);
      color: #fff;
      padding: 24px 28px;
    }
    .fee-card-header h3 { font-size: 1.05rem; font-weight: 700; margin: 0; }
    .fee-card-header p { font-size: .85rem; opacity: .8; margin: 4px 0 0; }
    .fee-table { width: 100%; border-collapse: collapse; }
    .fee-table td { padding: 14px 28px; font-size: .92rem; border-bottom: 1px solid #f1f5f9; color: #475569; }
    .fee-table td:last-child { text-align: right; font-weight: 600; color: #1b3557; }
    .fee-table tr:last-child td { border-bottom: none; }
    .fee-table tr:hover td { background: #f8fafc; }
    .fee-total td { background: #f0f4ff !important; font-weight: 700 !important; color: #1b3557 !important; }
    .free-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(16,185,129,.12);
      color: #065f46;
      font-size: .78rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 999px;
    }
  </style>
</head>
<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" width="40" height="40" decoding="async"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSION OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Dalubhasaang Politekniko ng Lungsod ng Baliwag</p>
        </div>
      </a>
      <nav class="nav-desktop hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News & Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="nav-actions flex items-center gap-3">
        <a href="{{ route('apply') }}" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
        <button id="menu-toggle" class="md:hidden p-2 rounded-lg" aria-label="Toggle menu"><i data-iconsax="menu"></i></button>
      </div>
    </div>
    <div id="mobile-menu" class="mobile-menu md:hidden">
      <div class="mobile-menu-inner">
        <nav class="mobile-nav">
          <a href="{{ route('home') }}" class="mobile-nav-link" style="--i:1"><i data-iconsax="home"></i><span>Home</span></a>
          <a href="{{ route('about') }}" class="mobile-nav-link" style="--i:2"><i data-iconsax="info-circle"></i><span>About</span></a>
          <a href="{{ route('home') }}#programs" class="mobile-nav-link" style="--i:3"><i data-iconsax="book"></i><span>Programs</span></a>
          <a href="{{ route('news-events') }}" class="mobile-nav-link" style="--i:4"><i data-iconsax="notification"></i><span>News & Events</span></a>
          <a href="{{ route('home') }}#contact" class="mobile-nav-link" style="--i:5"><i data-iconsax="message"></i><span>Contact Us</span></a>
        </nav>
        <div class="mobile-menu-footer" style="--i:6">
          <a href="{{ route('apply') }}" class="mobile-btn-primary"><span>Inquire Now</span><i data-iconsax="arrow-right"></i></a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="hero-section subpage-hero relative min-h-[45vh] flex items-center overflow-hidden">
      <div class="hero-bg-overlay absolute top-0 left-0 right-0 bottom-0"></div>
      <div class="hero-pattern absolute top-0 left-0 right-0 bottom-0"></div>
      <div class="relative z-10 max-w-7xl mx-auto px-8 w-full pt-24 pb-12">
        <div class="max-w-3xl">
          <div class="inline-flex items-center gap-2 pill-badge mb-8" data-animate="fade-up">
            <span class="pill-dot"></span>
            <span class="text-xs font-semibold tracking-widest uppercase">Cost of Education</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">Tuition</span>
            <span class="block text-line-2 italic">&amp; Fees</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            BTECH offers accessible and affordable quality education. Under RA 10931, tuition and other school fees are FREE for qualified Filipino students.
          </p>
        </div>
      </div>
    </section>

    <section class="py-28">
      <div class="max-w-5xl mx-auto px-8">

        <div class="feature-card feature-card--accent mb-14 flex gap-5 items-start" data-animate="fade-up" style="padding:28px 32px;">
          <div class="feature-icon-wrap" style="flex-shrink:0;"><i data-iconsax="shield-check"></i></div>
          <div>
            <h3 class="feature-title mt-0">Free Tuition Under RA 10931</h3>
            <p class="feature-desc mt-2">Baliwag Polytechnic College (BTECH) is a government-funded state university. Under the <strong>Universal Access to Quality Tertiary Education Act (RA 10931)</strong>, tuition and other school fees are <strong>FREE</strong> for all qualified Filipino students enrolled in government-offered programs.</p>
            <span class="free-badge mt-4 inline-flex"><i data-iconsax="check-circle" style="width:13px;height:13px;"></i> No Tuition Fee for Qualified Students</span>
          </div>
        </div>

        <div class="section-header text-center mb-12" data-animate="fade-up">
          <span class="section-tag">Miscellaneous Fees</span>
          <h2 class="section-title mt-3">Other Fees &amp; Charges</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">While tuition is free, some miscellaneous fees may apply per semester. Amounts are subject to change. Contact the Registrar's Office for the official and current schedule of fees.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          <div class="fee-card" data-animate="fade-up">
            <div class="fee-card-header">
              <h3>Per Semester Fees</h3>
              <p>Approximate schedule — verify with Registrar</p>
            </div>
            <table class="fee-table">
              <tr><td>Registration Fee</td><td>As assessed</td></tr>
              <tr><td>Laboratory Fee (if applicable)</td><td>As assessed</td></tr>
              <tr><td>Student Organization Fee</td><td>As assessed</td></tr>
              <tr><td>Library Fee</td><td>As assessed</td></tr>
              <tr><td>ID Fee (First Year)</td><td>As assessed</td></tr>
              <tr class="fee-total"><td>Tuition Fee</td><td>FREE ✓</td></tr>
            </table>
          </div>

          <div class="fee-card" data-animate="fade-up" data-delay="80">
            <div class="fee-card-header">
              <h3>Other Charges</h3>
              <p>One-time or conditional fees</p>
            </div>
            <table class="fee-table">
              <tr><td>Entrance Examination</td><td>As assessed</td></tr>
              <tr><td>Medical / Health Certificate</td><td>As assessed</td></tr>
              <tr><td>Graduation Fee (Final Year)</td><td>As assessed</td></tr>
              <tr><td>Late Enrollment Penalty</td><td>As assessed</td></tr>
              <tr><td>Transcript of Records</td><td>As assessed</td></tr>
              <tr><td>Other Certifications</td><td>As assessed</td></tr>
            </table>
          </div>
        </div>

        <div class="mt-12 p-8 bg-amber-50 border border-amber-200 rounded-2xl" data-animate="fade-up">
          <div class="flex gap-4 items-start">
            <div style="flex-shrink:0; margin-top:2px;"><i data-iconsax="info-circle" style="width:22px;height:22px;color:#b45309;"></i></div>
            <div>
              <p class="font-semibold mb-2" style="color:#92400e;">Disclaimer</p>
              <p class="text-sm leading-relaxed" style="color:#92400e;">The fee schedule above is for reference only. Actual fees are subject to annual review and may change per the policies of BTECH and CHED. For the official and updated fee schedule, please contact the BTECH Registrar's Office or Admissions Office directly.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="apply-cta-section py-28">
      <div class="apply-cta-inner max-w-7xl mx-auto px-8 relative overflow-hidden rounded-3xl">
        <div class="apply-cta-bg"></div>
        <div class="apply-cta-pattern"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12 py-20 px-8">
          <div class="text-center lg:text-left">
            <h2 class="apply-cta-title">Questions About Fees?<br><em>We Can Help.</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Our admissions team is ready to assist you with any questions about tuition, fees, and financial assistance.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('scholarship-programs') }}" class="btn-cta-ghost">View Scholarships <i data-iconsax="arrow-right"></i></a>
          </div>
        </div>
      </div>
    </section>
  </main>

 <!-- ───────────────────────────────────── FOOTER ───────────────────────────────────── -->
  @include('partials.footer')
  <button id="back-to-top" class="back-to-top" aria-label="Back to top"><i data-iconsax="arrow-up"></i></button>
  <script src="{{ asset('js/home-page.js') }}?v=8"></script>
  <script>if (window.iconsax) iconsax.createIcons();</script>
</body>
</html>
