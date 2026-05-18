<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Scholarship Programs — BTECH Admission Office</title>
  <meta name="description" content="Learn about scholarship programs available at Baliwag Polytechnic College for qualified and deserving students.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=22" />
  <style>
    #navbar:not(.scrolled) {
      background: rgba(3, 16, 36, 0.98) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 2px 24px rgba(0, 0, 0, .18);
    }

    #navbar:not(.scrolled) .nav-sub {
      color: rgba(255, 255, 255, .7) !important;
    }

    #navbar:not(.scrolled) .nav-main {
      color: #ffffff !important;
    }

    #navbar:not(.scrolled) .nav-link {
      color: rgba(255, 255, 255, .75) !important;
    }

    #navbar:not(.scrolled) .nav-link:hover {
      color: #ffffff !important;
    }

    #navbar:not(.scrolled) #menu-toggle {
      color: #ffffff;
    }

    .schol-card {
      background: #fff;
      border: 1px solid rgba(148, 163, 184, .25);
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 4px 20px rgba(15, 30, 61, .06);
      transition: transform .2s ease, box-shadow .2s ease;
      position: relative;
      overflow: hidden;
    }

    .schol-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #071b3d, #d99a22);
    }

    .schol-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 48px rgba(15, 30, 61, .12);
    }

    .schol-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(217, 154, 34, .12);
      color: #a36b00;
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 999px;
      margin-bottom: 14px;
    }

    .schol-title {
      font-size: 1.15rem;
      font-weight: 700;
      color: #1b3557;
      margin-bottom: 10px;
    }

    .schol-desc {
      color: #475569;
      font-size: .95rem;
      line-height: 1.7;
    }

    .schol-benefits {
      margin-top: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .schol-benefit {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: .88rem;
      color: #334155;
      font-weight: 500;
    }

    .schol-benefit-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #071b3d;
      flex-shrink: 0;
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="40" height="40" decoding="async"></div>
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
            <span class="text-xs font-semibold tracking-widest uppercase">Financial Assistance</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">Scholarship</span>
            <span class="block text-line-2 italic">Programs</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            BTECH is committed to making quality education accessible. Explore scholarship opportunities for deserving students.
          </p>
        </div>
      </div>
    </section>

    <section class="py-28">
      <div class="max-w-6xl mx-auto px-8">
        <div class="section-header text-center mb-16" data-animate="fade-up">
          <span class="section-tag">Available Scholarships</span>
          <h2 class="section-title mt-3">Financial Aid & Scholarships</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">The following scholarship programs are available to qualified BTECH students. Contact the Admissions Office for current availability and application procedures.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
          @php
          $scholarships = [
          [
          'badge' => 'Government-Funded',
          'title' => 'Universal Access to Quality Tertiary Education (UAQTE / Free Tuition)',
          'desc' => 'Under Republic Act 10931, BTECH offers free tuition and other school fees for qualified Filipino students enrolled in government-funded programs.',
          'benefits' => ['Free tuition and miscellaneous fees', 'Available to all Filipino students', 'Must maintain satisfactory academic standing'],
          ],
          [
          'badge' => 'Government-Funded',
          'title' => 'Tertiary Education Subsidy (TES)',
          'desc' => 'The TES program by CHED provides additional financial assistance beyond free tuition for students from low-income families.',
          'benefits' => ['Monthly stipend for qualified recipients', 'Covers living and educational allowance', 'Administered by CHED and UNIFAST'],
          ],
          [
          'badge' => 'Government Program',
          'title' => 'Pantawid Pamilyang Pilipino Program (4Ps)',
          'desc' => 'Students who are 4Ps beneficiaries are entitled to additional educational support and priority during the enrollment process.',
          'benefits' => ['Educational cash grants for 4Ps beneficiaries', 'Priority assistance during enrollment', 'Subject to DSWD eligibility'],
          ],
          [
          'badge' => 'Institutional',
          'title' => 'BTECH Academic Excellence Scholarship',
          'desc' => 'Awarded to incoming students who demonstrated exceptional academic performance during Senior High School, recognizing their hard work and dedication.',
          'benefits' => ['Partial to full tuition subsidy', 'Requires minimum GWA of 90 or above', 'Renewable each semester based on performance'],
          ],
          [
          'badge' => 'Government-Funded',
          'title' => 'CHED Merit Scholarship',
          'desc' => 'The CHED Merit Scholarship is awarded to academically excellent students who meet the qualifying criteria set by the Commission on Higher Education.',
          'benefits' => ['Full tuition and monthly stipend', 'Based on academic merit and entrance exam', 'Renewable per semester'],
          ],
          [
          'badge' => 'Special Program',
          'title' => 'PWD & Solo Parent Assistance',
          'desc' => 'BTECH provides special assistance and priority services for students with disabilities and solo parent beneficiaries in accordance with national law.',
          'benefits' => ['Discounts and priority processing', 'Access to special accommodations', 'Subject to submission of valid certification'],
          ],
          ];
          @endphp

          @foreach($scholarships as $i => $schol)
          <div class="schol-card" data-animate="fade-up" data-delay="{{ ($i % 2) * 80 }}">
            <div class="schol-badge">
              <i data-iconsax="star" style="width:12px;height:12px;"></i>
              {{ $schol['badge'] }}
            </div>
            <h3 class="schol-title">{{ $schol['title'] }}</h3>
            <p class="schol-desc">{{ $schol['desc'] }}</p>
            <div class="schol-benefits">
              @foreach($schol['benefits'] as $benefit)
              <div class="schol-benefit">
                <div class="schol-benefit-dot"></div>
                {{ $benefit }}
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>

        <div class="mt-16 p-8 bg-blue-50 border border-blue-200 rounded-2xl" data-animate="fade-up">
          <div class="flex gap-4 items-start">
            <div style="flex-shrink:0;"><i data-iconsax="info-circle" style="width:24px;height:24px;color:#1b3557;"></i></div>
            <div>
              <p class="font-semibold text-navy mb-2" style="color:#1b3557;">Note on Scholarship Availability</p>
              <p class="text-sm text-slate-600 leading-relaxed">Scholarship availability, requirements, and application procedures may change per semester or academic year. Please visit or contact the BTECH Admissions Office directly for the most up-to-date information and to confirm your eligibility.</p>
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
            <h2 class="apply-cta-title">Interested in a Scholarship?<br><em>Talk to Us.</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Our admissions team can guide you through the scholarship application and eligibility requirements.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('home') }}#contact" class="btn-cta-ghost">Contact Admissions <i data-iconsax="arrow-right"></i></a>
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