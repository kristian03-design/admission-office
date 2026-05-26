<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>How to Apply — BTECH Admission Office</title>
  <meta name="description" content="Step-by-step guide on how to apply to Baliwag Polytechnic College. Learn about the admission process and requirements.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=37" />
  <style>
    .step-card {
      display: flex;
      gap: 24px;
      align-items: flex-start;
      background: #fff;
      border: 1px solid rgba(148, 163, 184, .25);
      border-radius: 18px;
      padding: 28px;
      box-shadow: 0 4px 20px rgba(15, 30, 61, .06);
      transition: transform .2s ease, box-shadow .2s ease;
    }

    .step-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 36px rgba(15, 30, 61, .1);
    }

    .step-number {
      flex-shrink: 0;
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, #031024, #071b3d 58%, #0b2d6b);
      color: #fff;
      font-size: 1.25rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .step-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #1b3557;
      margin-bottom: 6px;
    }

    .step-desc {
      color: #475569;
      font-size: .95rem;
      line-height: 1.7;
    }

    .req-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #f0f4ff;
      color: #2a5298;
      font-size: .8rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 999px;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="74" height="74" decoding="async" onerror="this.remove()"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSIONS OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Baliwag Polytechnic College</p>
        </div>
      </a>
      <nav class="nav-desktop hidden lg:flex items-center gap-8">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News & Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="nav-actions flex items-center gap-3">
        <a href="{{ route('apply') }}" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
        <button id="menu-toggle" class="lg:hidden p-2 rounded-lg" aria-label="Toggle menu" aria-expanded="false">
          <span class="hamburger-icon" aria-hidden="true"></span>
        </button>
      </div>
    </div>
    <div id="mobile-menu" class="mobile-menu lg:hidden">
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
            <span class="text-xs font-semibold tracking-widest uppercase">Admission Process</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">How to</span>
            <span class="block text-line-2 italic">Apply</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            Follow these simple steps to begin your academic journey at Baliwag Polytechnic College.
          </p>
        </div>
      </div>
      </div>
    </section>

    <section class="py-28">
      <div class="max-w-4xl mx-auto px-8">
        <div class="section-header text-center mb-16" data-animate="fade-up">
          <span class="section-tag">Step by Step</span>
          <h2 class="section-title mt-3">Admission Process</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">Our admission process is straightforward. Here's everything you need to know to get started.</p>
        </div>

        <div class="flex flex-col gap-6">
          @php
          $steps = [
          ['num' => 1, 'title' => 'Submit Your Application', 'desc' => 'Fill out the online application form at this website or visit the Admissions Office in person. Make sure all fields are complete and accurate.', 'tag' => 'Online or Walk-in', 'icon' => 'edit'],
          ['num' => 2, 'title' => 'Prepare Your Documents', 'desc' => 'Gather all required documents including your Form 138, PSA Birth Certificate, good moral certificate, and 2x2 ID photos. See the Requirements page for the full list.', 'tag' => 'Document Submission', 'icon' => 'document'],
          ['num' => 3, 'title' => 'Submit Requirements', 'desc' => 'Bring or send your complete documents to the Admissions Office for verification. Incomplete submissions will be returned.', 'tag' => 'Verification', 'icon' => 'clipboard-check'],
          ['num' => 4, 'title' => 'Attend the Interview / Evaluation', 'desc' => 'You will be scheduled for an interview or evaluation with the Program Director of your chosen course. This helps confirm your readiness and suitability for the program.', 'tag' => 'Interview', 'icon' => 'users'],
          ['num' => 5, 'title' => 'Receive Your Admission Result', 'desc' => 'After evaluation, you will receive your admission status. Accepted applicants will be given instructions for enrollment.', 'tag' => 'Decision', 'icon' => 'check-circle'],
          ['num' => 6, 'title' => 'Proceed to Enrollment', 'desc' => 'Once accepted, follow the enrollment instructions provided by the Admissions Office to complete your registration for the upcoming semester.', 'tag' => 'Enrollment', 'icon' => 'star'],
          ];
          @endphp

          @foreach($steps as $i => $step)
          <div class="step-card" data-animate="fade-up" data-delay="{{ $i * 80 }}">
            <div class="step-number">{{ $step['num'] }}</div>
            <div>
              <div class="step-title">{{ $step['title'] }}</div>
              <p class="step-desc">{{ $step['desc'] }}</p>
              <span class="req-tag"><i data-iconsax="{{ $step['icon'] }}" style="width:13px;height:13px;"></i> {{ $step['tag'] }}</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="apply-cta-section py-28">
      <div class="apply-cta-inner max-w-7xl mx-auto px-8 relative overflow-hidden rounded-3xl">
        <div class="apply-cta-bg"></div>
        <div class="apply-cta-pattern"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12 py-20 px-8">
          <div class="text-center lg:text-left">
            <h2 class="apply-cta-title">Ready to Start?<br><em>Apply Today.</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Begin your application online or visit our admissions office for personalized guidance.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('requirements') }}" class="btn-cta-ghost">View Requirements <i data-iconsax="arrow-right"></i></a>
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