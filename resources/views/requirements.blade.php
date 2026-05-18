<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admission Requirements — BTECH Admission Office</title>
  <meta name="description" content="Complete list of admission requirements for Baliwag Polytechnic College. Check what documents you need to submit.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=26" />
  <style>
.req-card {
      background: #fff;
      border: 1px solid rgba(148, 163, 184, .25);
      border-radius: 18px;
      padding: 32px;
      box-shadow: 0 4px 20px rgba(15, 30, 61, .06);
    }

    .req-card-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: #1b3557;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
    }

    .req-card-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: linear-gradient(135deg, #031024, #071b3d 58%, #0b2d6b);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      flex-shrink: 0;
    }

    .req-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .req-list li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: .95rem;
      color: #475569;
      line-height: 1.6;
    }

    .req-list li::before {
      content: '';
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #d99a22;
      flex-shrink: 0;
      margin-top: 7px;
    }

    .note-box {
      background: #fef3c7;
      border: 1px solid #fcd34d;
      border-radius: 14px;
      padding: 20px 24px;
      display: flex;
      gap: 14px;
      align-items: flex-start;
    }

    .note-box-icon {
      color: #b45309;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .note-box p {
      color: #92400e;
      font-size: .9rem;
      line-height: 1.7;
      margin: 0;
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo-header.png') }}" alt="BTECH Logo" width="52" height="52" decoding="async" onerror="this.remove()"></div>
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
            <span class="text-xs font-semibold tracking-widest uppercase">What You Need</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">Admission</span>
            <span class="block text-line-2 italic">Requirements</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            Make sure your documents are complete before submitting your application to avoid delays.
          </p>
        </div>
      </div>
    </section>

    <section class="py-28">
      <div class="max-w-5xl mx-auto px-8">
        <div class="section-header text-center mb-16" data-animate="fade-up">
          <span class="section-tag">Documents Needed</span>
          <h2 class="section-title mt-3">What to Prepare</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">Requirements may vary by program. Please check with the Admissions Office if you have specific concerns.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          <div class="req-card" data-animate="fade-up">
            <div class="req-card-title">
              <div class="req-card-icon"><i data-iconsax="document" style="width:20px;height:20px;"></i></div>
              For New Students (SHS Graduates)
            </div>
            <ul class="req-list">
              <li>Original copy of Senior High School (SHS) Form 138 / Report Card</li>
              <li>Certificate of Good Moral Character from the last school attended</li>
              <li>PSA-issued Birth Certificate (original or certified true copy)</li>
              <li>4 pcs. 2×2 ID photo (white background, recent)</li>
              <li>Photocopy of valid government-issued ID (or parent/guardian ID)</li>
            </ul>
          </div>

          <div class="req-card" data-animate="fade-up" data-delay="80">
            <div class="req-card-title">
              <div class="req-card-icon"><i data-iconsax="refresh" style="width:20px;height:20px;"></i></div>
              For Transferees
            </div>
            <ul class="req-list">
              <li>Honorable Dismissal / Transfer Credential from previous school</li>
              <li>Transcript of Records (TOR) or True Copy of Grades</li>
              <li>Certificate of Good Moral Character</li>
              <li>PSA-issued Birth Certificate</li>
              <li>4 pcs. 2×2 ID photo (white background)</li>
              <li>Photocopy of valid ID</li>
            </ul>
          </div>

          <div class="req-card" data-animate="fade-up" data-delay="160">
            <div class="req-card-title">
              <div class="req-card-icon"><i data-iconsax="repeat" style="width:20px;height:20px;"></i></div>
              For Second Courser / Returnees
            </div>
            <ul class="req-list">
              <li>Transcript of Records (TOR) from previous college/university</li>
              <li>Honorable Dismissal or Certificate of Completion</li>
              <li>Certificate of Good Moral Character</li>
              <li>PSA-issued Birth Certificate</li>
              <li>4 pcs. 2×2 ID photo</li>
              <li>Photocopy of valid government-issued ID</li>
            </ul>
          </div>

          <div class="req-card" data-animate="fade-up" data-delay="240">
            <div class="req-card-title">
              <div class="req-card-icon"><i data-iconsax="star" style="width:20px;height:20px;"></i></div>
              Additional Documents (If Applicable)
            </div>
            <ul class="req-list">
              <li>PWD ID or medical certificate (for persons with disability)</li>
              <li>Certificate of Indigenous Peoples (IP) status (for IP applicants)</li>
              <li>4Ps / DSWD certification (for 4Ps beneficiaries)</li>
              <li>Marriage Certificate (if applicable)</li>
            </ul>
          </div>
        </div>

        <div class="note-box mt-12" data-animate="fade-up">
          <div class="note-box-icon"><i data-iconsax="info-circle" style="width:22px;height:22px;"></i></div>
          <p><strong>Important:</strong> All documents must be original or certified true copies. Photocopies alone will not be accepted for official submission. For special cases or scholarship-related requirements, please contact the Admissions Office directly.</p>
        </div>
      </div>
    </section>

    <section class="apply-cta-section py-28">
      <div class="apply-cta-inner max-w-7xl mx-auto px-8 relative overflow-hidden rounded-3xl">
        <div class="apply-cta-bg"></div>
        <div class="apply-cta-pattern"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12 py-20 px-8">
          <div class="text-center lg:text-left">
            <h2 class="apply-cta-title">Documents Ready?<br><em>Apply Now.</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Submit your application online or visit us at the Admissions Office. We're here to help.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('how-to-apply') }}" class="btn-cta-ghost">How to Apply <i data-iconsax="arrow-right"></i></a>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ───────────────────────────────────── FOOTER ───────────────────────────────────── -->
  @include('partials.footer')
  <button id="back-to-top" class="back-to-top" aria-label="Back to top"><i data-iconsax="arrow-up"></i></button>
  <script src="{{ asset('js/home-page.js') }}?v=8"></script>
  <script>
    if (window.iconsax) iconsax.createIcons();
  </script>
</body>

</html>