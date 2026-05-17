<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Content-Security-Policy"
    content="script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://cdn.tailwindcss.com;">
  <title>BTECH — Admissions Office</title>
  <script>
    window.ICONSAX_SPRITE_PATH = "{{ asset('assets/iconsax-sprite.svg') }}";
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>


  <!-- ✦ Iconsax Icons ✦ -->
  @include('partials.iconsax')

  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=20" />

</head>

<body>
  @include('partials.site-loader')

  <!-- ───────────────────────────────────── NAV ───────────────────────────────────── -->
  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" width="40" height="40" decoding="async"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSION OFFICE' }}</p>
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
  <main>

    <!-- ───────────────────────────────────── HERO ───────────────────────────────────── -->
    <section id="hero" class="hero-section relative min-h-[85vh] flex items-center overflow-hidden">
      <div class="hero-bg-overlay" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>
      <div class="hero-pattern" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>

      <div class="deco-circle deco-circle-1"></div>
      <div class="deco-circle deco-circle-2"></div>
      <div class="deco-line deco-line-1"></div>
      <div class="deco-line deco-line-2"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-8 w-full pt-20 pb-16">
        <div class="hero-grid">

          <!-- LEFT: Text -->
          <div class="hero-content">
            <div class="inline-flex items-center gap-2 pill-badge mb-8" data-animate="fade-up" data-delay="0">
              <span class="pill-dot"></span>
              <span class="text-xs font-semibold tracking-widest uppercase">{{ $settings['school_year_label'] ?? 'Admissions Open · S.Y. 2026–2027' }}</span>
            </div>

            <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
              @if(isset($settings['hero_headline']))
              {!! nl2br(e($settings['hero_headline'])) !!}
              @else
              <span class="block text-line-1">Begin Your</span>
              <span class="block text-line-2 italic">Journey</span>
              <span class="block text-line-3">Here.</span>
              @endif
            </h1>

            <div class="hero-program-label" data-animate="fade-up" data-delay="150">
              <span class="program-label-dot"></span>
              <span id="hero-program-name">Hospitality Management</span>
            </div>

            <p class="hero-sub mt-4 text-lg leading-relaxed max-w-lg" data-animate="fade-up" data-delay="200">
              {{ $settings['hero_subheadline'] ?? 'Baliwag Polytechnic College has been shaping futures through quality, accessible higher education for over a decade. Your story starts with a single step.' }}
            </p>

            <div class="flex flex-wrap gap-4 mt-10" data-animate="fade-up" data-delay="300">
              <a href="{{ route('apply') }}?fresh=true" class="btn-hero-primary group">
                <span>{{ $settings['cta_text'] ?? 'Start Your Application' }}</span>
                <i data-iconsax="arrow-right"></i>
              </a>
              <a href="#process" class="btn-hero-secondary group">
                <span>How It Works</span>
                <i data-iconsax="chevron-down" style="opacity:.6"></i>
              </a>
            </div>

            <script type="application/json" id="hero-slides-json">@json($heroSlides ?? [])</script>

            <!-- Slider dots -->
            <div class="hero-slider-nav mt-10" id="hero-slider-nav" data-animate="fade-up" data-delay="350"></div>

            <div class="hero-stats mt-10 grid grid-cols-3 gap-6" data-animate="fade-up" data-delay="400">
              <div class="stat-item">
                <span class="stat-number" data-count="{{ now()->year - ($settings['institution_founding_year'] ?? 2008) }}">0</span>
                <span class="stat-plus">+</span>
                <p class="stat-label">Years of Excellence</p>
              </div>
              <div class="stat-item">
                <span class="stat-number" data-count="{{ $settings['alumni_count_k'] ?? '8' }}">0</span>
                <span class="stat-plus">k+</span>
                <p class="stat-label">Alumni Worldwide</p>
              </div>
              <div class="stat-item">
                <span class="stat-number" data-count="{{ $programs->count() }}">0</span>
                <p class="stat-label">Degree Programs</p>
              </div>
            </div>
          </div>

          <!-- RIGHT: Rotating images -->
          <div class="hero-visual" data-animate="fade-up" data-delay="200">
            <div class="hero-visual-ring"></div>
            <div class="hero-visual-ring hero-visual-ring--2"></div>

            <div class="hero-badge-float" id="hero-badge-float">
              <div>
                <p class="hero-badge-title" id="hero-badge-title">{{ $heroSlides[0]['department'] ?? 'Academic Programs' }}</p>
              </div>
            </div>

            <div class="hero-img-stage" id="hero-img-stage"></div>

            <div class="hero-progress-wrap">
              <div class="hero-progress-bar" id="hero-progress-bar"></div>
            </div>
          </div>

        </div>
      </div>

      <div class="scroll-indicator absolute bottom-8 left-1/2 -translate-x-1/2">
        <div class="scroll-mouse">
          <div class="scroll-dot"></div>
        </div>
      </div>
    </section>

    <!-- ───────────────────────────────────── TICKER ───────────────────────────────────── -->
    @php
    $tickerAnnouncements = ($tickerAnnouncements ?? $announcements->where('is_popup', false)->values())
    ->unique(fn ($ann) => trim(mb_strtolower($ann->message ?? '')))
    ->values();
    @endphp
    <div class="ticker-bar">
      <div class="ticker-inner">
        <span class="ticker-label">ANNOUNCEMENTS</span>
        <div class="ticker-track-wrap">
          <div class="ticker-track" id="ticker">
            @if($tickerAnnouncements->isNotEmpty())
            @foreach($tickerAnnouncements as $ann)
            <span class="ticker-item">
              @if($ann->popup_image)
              <img src="{{ str_starts_with($ann->popup_image, 'http') ? $ann->popup_image : asset(str_starts_with($ann->popup_image, 'storage/') || str_starts_with($ann->popup_image, '/storage/') ? ltrim($ann->popup_image, '/') : 'storage/' . $ann->popup_image) }}" alt="" class="ticker-image" loading="lazy" decoding="async" width="34" height="34">
              @endif
              <span>{{ $ann->message }}</span>
            </span>
            @endforeach
            <!-- Clone for seamless scroll animation; hidden from assistive tech. -->
            @foreach($tickerAnnouncements as $ann)
            <span class="ticker-item" aria-hidden="true">
              @if($ann->popup_image)
              <img src="{{ str_starts_with($ann->popup_image, 'http') ? $ann->popup_image : asset(str_starts_with($ann->popup_image, 'storage/') || str_starts_with($ann->popup_image, '/storage/') ? ltrim($ann->popup_image, '/') : 'storage/' . $ann->popup_image) }}" alt="" class="ticker-image" loading="lazy" decoding="async" width="34" height="34">
              @endif
              <span>{{ $ann->message }}</span>
            </span>
            @endforeach
            @else
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- ───────────────────────────────────── WHY BTECH ───────────────────────────────────── -->
    <section id="why" class="why-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="section-header text-center mb-20" data-animate="fade-up">
          <span class="section-tag">Why Choose BTECH</span>
          <h2 class="section-title mt-3">More Than a Degree.<br />A Launchpad.</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">
            We combine academic rigor with real-world readiness, preparing graduates who don't just find jobs — they lead industries.
          </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          @for($i = 1; $i <= 6; $i++)
            @php
            $title=$settings["feature_{$i}_title"] ?? null;
            $desc=$settings["feature_{$i}_desc"] ?? null;
            $icon=$settings["feature_{$i}_icon"] ?? null;

            if (!$title) {
            $fallbacks=[
            1=> ['title' => 'CHED-Recognized', 'desc' => 'All degree programs are fully accredited by CHED, ensuring academic excellence, industry relevance, and nationwide recognition of your qualifications.', 'icon' => 'graduation-cap'],
            2 => ['title' => 'Expert Faculty', 'desc' => 'Learn from highly qualified educators and industry professionals who provide practical insights, mentorship, and real-world expertise.', 'icon' => 'users-round'],
            3 => ['title' => 'Scholarship Support', 'desc' => 'We offer accessible scholarship and financial assistance programs designed to help deserving students achieve their academic goals.', 'icon' => 'badge-percent'],
            4 => ['title' => 'Modern Facilities', 'desc' => 'Experience a dynamic learning environment with advanced laboratories, smart classrooms, and collaborative spaces equipped for modern education.', 'icon' => 'building-2'],
            5 => ['title' => 'Industry Partnerships', 'desc' => 'Build valuable career opportunities through strong partnerships with leading local and national companies for internships, training, and employment.', 'icon' => 'handshake'],
            6 => ['title' => 'Strategic Location', 'desc' => 'Strategically located in Baliwag, Bulacan, providing convenient access to quality education and opportunities across the region.', 'icon' => 'map-pin'],
            ];
            $title = $fallbacks[$i]['title'];
            $desc = $fallbacks[$i]['desc'];
            $icon = $fallbacks[$i]['icon'];
            }
            $isDark = ($i === 6);
            $isAccent = ($i === 1);
            @endphp
            <div class="feature-card {{ $isDark ? 'feature-card--dark' : ($isAccent ? 'feature-card--accent' : '') }}" data-animate="fade-up" data-delay="{{ ($i-1) * 80 }}">
              <div class="feature-icon-wrap {{ $isDark ? 'feature-icon-wrap--light' : '' }}">
                <i data-iconsax="{{ $icon }}"></i>
              </div>
              <h3 class="feature-title {{ $isDark ? 'feature-title--light' : '' }} mt-5">{{ $title }}</h3>
              <p class="feature-desc {{ $isDark ? 'feature-desc--light' : '' }} mt-2">{{ $desc }}</p>
            </div>
            @endfor
        </div>
      </div>
    </section>

    <!-- ───────────────────────────────────── PROGRAMS ───────────────────────────────────── -->
    <section id="programs" class="programs-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16" data-animate="fade-up">
          <div>
            <span class="section-tag">Academic Programs</span>
            <h2 class="section-title mt-3">Find Your Path</h2>
          </div>
          <div class="flex gap-2 program-filters" role="tablist">
            <button class="filter-btn active" data-filter="all" role="tab">All Programs</button>
            <button class="filter-btn" data-filter="technology" role="tab">Technology</button>
            <button class="filter-btn" data-filter="business" role="tab">Business</button>
            <button class="filter-btn" data-filter="education" role="tab">Education</button>
            <button class="filter-btn" data-filter="hospitality" role="tab">Hospitality</button>
            <button class="filter-btn" data-filter="accountancy" role="tab">Accountancy</button>
            <button class="filter-btn" data-filter="arts&sciences" role="tab">Arts & Sciences</button>
          </div>
        </div>

        <div class="programs-grid" id="programs-grid">
          @foreach($programs as $index => $program)
          @php
          $category = strtolower(str_replace(' ', '', $program->department ?? ''));
          if ($category === 'arts&sciences') $category = 'arts&sciences';

          $badgeClass = match($category) {
          'technology' => 'badge--tech',
          'business' => 'badge--biz',
          'accountancy' => 'badge--acc',
          'education' => 'badge--edu',
          'hospitality' => 'badge--hosp',
          'arts&sciences' => 'badge--arts',
          default => 'badge--biz'
          };
          @endphp
          <div class="program-card" data-category="{{ $category }}" data-animate="fade-up" data-delay="{{ ($index % 6) * 60 }}">
            <div class="program-card-inner">
              <div class="program-badge {{ $badgeClass }}">{{ $program->department }}</div>
              <h3 class="program-name mt-4">{{ $program->name }}</h3>
              <p class="program-desc mt-2">
                {{ $program->description ?? 'Join our ' . $program->name . ' program and build a successful career in ' . $program->department . '.' }}
              </p>
              <div class="program-meta mt-4">
                <span class="meta-item"><i data-iconsax="clock"></i> 4 Years</span>
                <span class="meta-item"><i data-iconsax="sun-moon"></i> Day</span>
                @php
                $slotsLeft = (int) ($program->slots_left ?? 0);
                $isOpen = $program->is_active && $slotsLeft > 0;
                @endphp
                <span class="meta-item {{ $isOpen ? 'program-status-open' : 'program-status-closed' }}">
                  <i data-iconsax="{{ $isOpen ? 'check-circle' : 'x' }}"></i>
                  {{ $isOpen ? 'Open' : ($slotsLeft <= 0 ? 'Full Slot' : 'Closed') }}
                </span>
              </div>
              @php
              $programHref = filled($program->id ?? null) ? route('programs.show', ['id' => $program->id]) : route('home') . '#contact';
              @endphp
              <a href="{{ $programHref }}" class="program-cta group">
                Learn more <i data-iconsax="chevron-right"></i>
              </a>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- ───────────────────────────────────── PROCESS ───────────────────────────────────── -->
    <section id="process" class="process-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="section-header text-center mb-20" data-animate="fade-up">
          <span class="section-tag">Admissions Process</span>
          <h2 class="section-title mt-3">Your Path to Enrollment</h2>
          <p class="section-desc mt-4 max-w-xl mx-auto">We've made the process simple, transparent, and welcoming. Here's everything you need to know.</p>
        </div>

        <div class="process-timeline">

          <!-- Step 01 -->
          <div class="process-step" data-animate="fade-right" data-delay="0">
            <div class="step-number-wrap">
              <div class="step-number">01</div>
              <div class="step-connector"></div>
            </div>
            <div class="step-content">
              <h3 class="step-title">Submit an Online Application Form</h3>
              <p class="step-desc">Fill out our online application form or visit the Admissions Office. Our staff will guide you on your program options and requirements.</p>

              <div class="step-requirements">
                <p class="req-label">Documents to Prepare (Freshmen):</p>
                <ul class="req-list">
                  <li>PSA Birth Certificate</li>
                  <li>Senior High School Report Card (Grade 11 &amp; 12)</li>
                  <li>Certificate of Good Moral Character</li>
                  <li>Original of Diploma</li>
                </ul>
                <ul class="req-list mt-2">
                  <li><strong>Note:</strong> Grade 12 students can submit a Certificate of Enrollment and provide the final report card and diploma once available.</li>
                </ul>
              </div>

              <div class="step-requirements">
                <p class="req-label">Documents to Prepare (Transferee):</p>
                <ul class="req-list">
                  <li>PSA Birth Certificate</li>
                  <li>Official Transcript of Records (TOR)</li>
                  <li>Certificate of Good Moral Character</li>
                  <li>Original of Diploma</li>
                  <li>Letter of Recommendation from Previous School</li>
                </ul>
                <ul class="req-list mt-2">
                  <li><strong>Note:</strong> Transferees must have completed at least one semester in their previous school.</li>
                </ul>
              </div>

              <div class="step-requirements">
                <p class="req-label">Documents to Prepare (ALS Graduate):</p>
                <ul class="req-list">
                  <li>PSA Birth Certificate</li>
                  <li>ALS Certificate of Completion</li>
                  <li>Certificate of Good Moral Character</li>
                  <li>PEPT/TPEP Certificate (if applicable)</li>
                </ul>
                <ul class="req-list mt-2">
                  <li><strong>Note:</strong> ALS graduates must have completed the ALS program within the last 2 years.</li>
                </ul>
              </div>

              <div class="step-requirements">
                <p class="req-label">Documents to Prepare (Returnee):</p>
                <ul class="req-list">
                  <li>PSA Birth Certificate</li>
                  <li>Official Transcript of Records (TOR) from BTECH</li>
                  <li>Certificate of Good Moral Character</li>
                  <li>Written Request for Re-enrollment</li>
                </ul>
                <ul class="req-list mt-2">
                  <li><strong>Note:</strong> Returnees must have been away from BTECH for at least one academic year.</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Step 02 -->
          <div class="process-step" data-animate="fade-right" data-delay="100">
            <div class="step-number-wrap">
              <div class="step-number">02</div>
              <div class="step-connector"></div>
            </div>
            <div class="step-content">
              <h3 class="step-title">Schedule of Interview for Qualified Applicants</h3>
              <p class="step-desc">Schedule and complete the BTECH College Admission Interview. This evaluates your academic readiness and helps us tailor your academic support.</p>
              <!-- step-highlight uses display:flex align-items:flex-start gap:.6rem in your CSS -->
              <div class="step-highlight">
                <i data-iconsax="calendar-days"></i>
                <span>Tests are held every <strong>Monday to Friday</strong> — 9:00 AM to 3:00 PM</span>
              </div>
            </div>
          </div>

          <!-- Step 03 -->
          <div class="process-step" data-animate="fade-right" data-delay="150">
            <div class="step-number-wrap">
              <div class="step-number">03</div>
              <div class="step-connector"></div>
            </div>
            <div class="step-content">
              <h3 class="step-title">Take the College Admission Test (CAT)</h3>
              <p class="step-desc">Schedule and complete the BTC College Admission Test. This evaluates your academic readiness and helps us tailor your academic support.</p>
              <div class="step-highlight">
                <i data-iconsax="clipboard-list"></i>
                <span>Schedule will be announced — <strong>TBA</strong></span>
              </div>
            </div>
          </div>

          <!-- Step 04 -->
          <div class="process-step" data-animate="fade-right" data-delay="200">
            <div class="step-number-wrap">
              <div class="step-number">04</div>
              <div class="step-connector"></div>
            </div>
            <div class="step-content">
              <h3 class="step-title">Receive Your Admission Result</h3>
              <p class="step-desc">Results are released within 3–5 working days. Qualifying students will receive an Admission Notice via email.</p>
              <div class="step-highlight">
                <i data-iconsax="mail-check"></i>
                <span>Results sent directly to your registered email and available at the Admissions Office.</span>
              </div>
            </div>
          </div>

          <!-- Step 05 -->
          <div class="process-step" data-animate="fade-right" data-delay="250">
            <div class="step-number-wrap">
              <div class="step-number">05</div>
              <div class="step-connector"></div>
            </div>
            <div class="step-content">
              <h3 class="step-title">Complete Enrollment &amp; Orientation</h3>
              <p class="step-desc">Present your Admission Notice, submit required documents, pay initial fees, and attend the New Student Orientation to begin your BTC journey.</p>
              <div class="step-highlight">
                <i data-iconsax="party-popper"></i>
                <span>Complete enrollment and orientation within <strong>1 week</strong> of receiving your admission notice.</span>
              </div>
              <div class="step-requirements" style="margin-top:1rem;padding:1rem;background:#fef3c7;border-left:4px solid #f59e0b;border-radius:var(--radius-sm);">
                <p class="req-label" style="color:#92400e;font-weight:700;">Take Note:</p>
                <p style="color:#92400e;margin-top:.5rem;font-size:.875rem;line-height:1.6;">Applicants who have not completed their required documents by the enrollment deadline will not be allowed to proceed with enrollment. Please ensure all required documents are submitted before the specified deadline.</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- ───────────────────────────────────── TESTIMONIALS ───────────────────────────────────── -->
    <section id="testimonials" class="testimonials-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="section-header text-center mb-16" data-animate="fade-up">
          <span class="section-tag">Student Stories</span>
          <h2 class="section-title mt-3">Life After BTECH</h2>
          <p class="section-desc mt-4 max-w-xl mx-auto">Hear directly from our graduates and current students about their BTECH experience.</p>
        </div>

        <div class="testimonials-carousel-wrap" data-animate="fade-up" data-delay="100">
          <div class="testimonials-carousel" id="testimonials-carousel">
            @forelse($testimonials as $testimonial)
            @php
            $authorParts = collect(explode(' ', trim($testimonial->author_name)))->filter()->values();
            $authorInitials = strtoupper(substr($authorParts->first() ?? 'B', 0, 1) . substr($authorParts->count() > 1 ? $authorParts->last() : ($authorParts->first() ?? 'T'), 0, 1));
            @endphp
            <div class="testimonial-card">
              <div class="testimonial-quote-icon">"</div>
              <p class="testimonial-text">{{ $testimonial->message }}</p>
              <div class="testimonial-author mt-6">
                @if($testimonial->author_avatar)
                @php
                $avatarUrl = str_starts_with($testimonial->author_avatar, 'http') ? $testimonial->author_avatar : asset(str_starts_with($testimonial->author_avatar, 'storage/') || str_starts_with($testimonial->author_avatar, '/storage/') ? ltrim($testimonial->author_avatar, '/') : 'storage/' . $testimonial->author_avatar);
                @endphp
                <div class="author-avatar" style="background-image: url('{{ $avatarUrl }}'); background-size: cover;"></div>
                @else
                <div class="author-avatar" style="background: var(--navy); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                  {{ $authorInitials }}
                </div>
                @endif
                <div>
                  <p class="author-name">{{ $testimonial->author_name }}</p>
                  <p class="author-role">{{ $testimonial->author_role }}</p>
                </div>
              </div>
            </div>
            @empty
            <div class="testimonial-card">
              <div class="testimonial-quote-icon">"</div>
              <p class="testimonial-text">BTECH gave me the technical foundation and the confidence to land my first IT job right after graduation. The professors genuinely care about your growth.</p>
              <div class="testimonial-author mt-6">
                <div class="author-avatar" style="background: var(--navy); color: white; display: flex; align-items: center; justify-content: center;">JR</div>
                <div>
                  <p class="author-name">Jose Reyes</p>
                  <p class="author-role">BSIT Graduate, 2023 – Software Developer</p>
                </div>
              </div>
            </div>
            @endforelse
          </div>

          <!-- Carousel controls — your JS targets #carousel-prev, #carousel-next, #carousel-dots -->
          <div class="carousel-controls mt-10 flex items-center justify-center gap-4">
            <button class="carousel-btn" id="carousel-prev" aria-label="Previous">
              <svg class="nav-arrow-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M15 6 9 12l6 6" />
              </svg>
            </button>
            <div class="carousel-dots" id="carousel-dots"></div>
            <button class="carousel-btn" id="carousel-next" aria-label="Next">
              <svg class="nav-arrow-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="m9 6 6 6-6 6" />
              </svg>
            </button>
          </div>
        </div>

        <!-- ───────────────────────────────────── APPLY CTA ───────────────────────────────────── -->
        <section id="apply" class="apply-cta-section py-28">
          <div class="apply-cta-inner max-w-7xl mx-auto px-8 relative overflow-hidden rounded-3xl">
            <div class="apply-cta-bg"></div>
            <div class="apply-cta-pattern"></div>
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12 py-20 px-8">
              <div class="text-center lg:text-left">
                <h2 class="apply-cta-title">{!! $settings['cta_section_headline'] ?? 'Your Future Begins<br /><em>This Enrollment Season.</em>' !!}</h2>
                <p class="apply-cta-sub mt-4 max-w-xl">{{ $settings['cta_section_subheadline'] ?? "Don't wait. Seats are limited and scholarship slots fill quickly. Take the first step toward the career — and the life — you've been working toward." }}</p>
                <div class="apply-deadline mt-6">
                  <span class="deadline-label">Application Deadline</span>
                  <span class="deadline-date">{{ $settings['application_deadline'] ?? 'April 17, 2026' }}</span>
                </div>
              </div>
              <div class="flex flex-col gap-4 min-w-64">
                <a href="{{ route('apply') }}?fresh=true" class="btn-cta-primary text-center">
                  {{ $settings['cta_section_button_text'] ?? 'Apply Online Now' }}
                  <i data-iconsax="arrow-right" style="display:inline-flex;margin-left:.5rem;vertical-align:middle;width:18px;height:18px;"></i>
                </a>
                <button id="open-guide" class="btn-cta-link text-center text-sm w-full">Admission Guidelines & Guide →</button>
              </div>
            </div>
          </div>
        </section>

        <!-- ───────────────────────────────────── CONTACT ───────────────────────────────────── -->
        <section id="contact" class="contact-section py-28">
          <div class="max-w-7xl mx-auto px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-start">

              <!-- Left: info -->
              <div data-animate="fade-right">
                <span class="section-tag">Get In Touch</span>
                <h2 class="section-title mt-3">We're Here to Help</h2>
                <p class="section-desc mt-4 max-w-lg">Have questions about programs, scholarships, or the admissions process? Our team is ready to assist you Monday through Friday.</p>

                <div class="contact-info mt-10 flex flex-col gap-5">

                  <!-- Address -->
                  <div class="contact-item">
                    <div class="contact-icon-wrap">
                      <i data-iconsax="map-pin"></i>
                    </div>
                    <div>
                      <p class="contact-label">Campus Address</p>
                      <p class="contact-value">{{ $settings['contact_address'] ?? 'Baliwag City, Bulacan 3006' }}</p>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="contact-item">
                    <div class="contact-icon-wrap">
                      <i data-iconsax="mail"></i>
                    </div>
                    <div>
                      <p class="contact-label">Email</p>
                      <a href="mailto:{{ $settings['admissions_email'] ?? 'admission@btech.edu.ph' }}" class="contact-value contact-link">{{ $settings['admissions_email'] ?? 'admission@btech.edu.ph' }}</a>
                    </div>
                  </div>

                  <!-- Phone -->
                  <div class="contact-item">
                    <div class="contact-icon-wrap">
                      <i data-iconsax="phone"></i>
                    </div>
                    <div>
                      <p class="contact-label">Phone</p>
                      <p class="contact-value">{{ $settings['contact_phone'] ?? '(044) 766 2222' }}</p>
                    </div>
                  </div>

                  <!-- Hours -->
                  <div class="contact-item">
                    <div class="contact-icon-wrap">
                      <i data-iconsax="clock"></i>
                    </div>
                    <div>
                      <p class="contact-label">Office Hours</p>
                      <p class="contact-value">{{ $settings['contact_office_hours'] ?? 'Mon–Fri, 8:00 AM – 5:00 PM' }}</p>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Right: form -->
              <div data-animate="fade-left">
                <div class="contact-form-card">
                  <h3 class="contact-form-title">Send Us a Message</h3>
                  <!-- JS targets #contact-form, .form-input[required], #contact-submit-btn, .btn-text, .btn-spinner -->
                  <form id="contact-form" class="mt-6 flex flex-col gap-4" novalidate>
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                      <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" placeholder="Maria" class="form-input" required />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" placeholder="Santos" class="form-input" required />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" placeholder="maria@email.com" class="form-input" required />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Subject</label>
                      <div class="select-wrap">
                        <select name="subject" class="form-select">
                          <option value="Admissions Inquiry">Admissions Inquiry</option>
                          <option value="Scholarship Information">Scholarship Information</option>
                          <option value="Program Details">Program Details</option>
                          <option value="Other">Other</option>
                        </select>
                        <!-- select-arrow is position:absolute right:.85rem, pointer-events:none in your CSS -->
                        <i data-iconsax="chevron-down" class="select-arrow"></i>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Message</label>
                      <textarea name="message" rows="4" placeholder="Tell us how we can help you..." class="form-input form-textarea" required></textarea>
                    </div>
                    <button type="submit" class="btn-form-submit mt-1" id="contact-submit-btn">
                      <!-- JS toggles .hidden on .btn-text and .btn-spinner -->
                      <span class="btn-text">Send Message</span>
                      <span class="btn-spinner hidden">
                        <i data-iconsax="loader-circle" class="animate-spin"></i>
                      </span>
                    </button>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </section>

  </main>

  <!-- ───────────────────────────────────── FOOTER ───────────────────────────────────── -->
  @include('partials.footer')

  <!-- Back to top — your CSS targets #back-to-top.visible -->
  <button id="back-to-top" class="back-to-top" aria-label="Back to top">
    <i data-iconsax="arrow-up"></i>
  </button>


  <!-- Initialize Iconsax after DOM + your JS have both run -->
  <!-- ─── ANNOUNCEMENT POPUP ─── -->
  @php
  $popupAnn = $popupAnn ?? $announcements->firstWhere('is_popup', true);
  @endphp

  @if($popupAnn)
  <div id="announcementPopup"
    data-id="{{ $popupAnn->id }}"
    data-always-show="{{ $popupAnn->popup_always_show ? 'true' : 'false' }}"
    data-cookie-days="30"
    class="announcement-popup"
    role="dialog"
    aria-modal="true"
    aria-labelledby="announcementPopupTitle"
    aria-describedby="announcementPopupMessage"
    hidden>

    <div class="announcement-popup__card" id="popupCard" tabindex="-1">
      <button type="button" class="announcement-popup__close" onclick="closePopup()" aria-label="Close announcement">
        <i data-iconsax="x"></i>
      </button>

      <div class="announcement-popup__media">
        @if($popupAnn->popup_image)
        <img src="{{ str_starts_with($popupAnn->popup_image, 'http') ? $popupAnn->popup_image : asset(str_starts_with($popupAnn->popup_image, 'storage/') || str_starts_with($popupAnn->popup_image, '/storage/') ? ltrim($popupAnn->popup_image, '/') : 'storage/' . $popupAnn->popup_image) }}" alt="Announcement" class="announcement-popup__image" loading="lazy" decoding="async">
        @else
        <div class="announcement-popup__fallback">
          <i data-iconsax="megaphone"></i>
          <span>Official Bulletin</span>
        </div>
        @endif

        <div class="announcement-popup__media-shade"></div>
      </div>

      <div class="announcement-popup__content">
        <span class="announcement-popup__badge">
          <i data-iconsax="notification"></i>
          Important Notice
        </span>

        <h2 id="announcementPopupTitle" class="announcement-popup__title">
          {{ $popupAnn->title ?? 'Announcement' }}
        </h2>

        <p id="announcementPopupMessage" class="announcement-popup__message">
          {{ $popupAnn->message }}
        </p>

        <div class="announcement-popup__actions">
          @if($popupAnn->popup_button_link)
          <a href="{{ $popupAnn->popup_button_link }}" class="announcement-popup__primary">
            <span>{{ $popupAnn->popup_button_text ?? 'Learn More' }}</span>
            <i data-iconsax="arrow-right"></i>
          </a>
          @endif
          <button type="button" onclick="closePopup()" class="announcement-popup__secondary">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function() {
      const popup = document.getElementById('announcementPopup');
      if (!popup) return;

      const cookieName = 'btech_announcement_' + popup.getAttribute('data-id');
      const alwaysShow = popup.getAttribute('data-always-show') === 'true';
      const cookieDays = parseInt(popup.getAttribute('data-cookie-days') || '30', 10);
      let lastFocusedElement = null;

      function getCookie(name) {
        return document.cookie
          .split('; ')
          .find((row) => row.startsWith(name + '='))
          ?.split('=')[1] || '';
      }

      function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
      }

      function openPopup() {
        lastFocusedElement = document.activeElement;
        popup.hidden = false;
        document.body.classList.add('announcement-popup-open');
        requestAnimationFrame(() => {
          const card = document.getElementById('popupCard');
          popup.classList.add('is-visible');
          card?.focus();
        });
      }

      window.closePopup = function closePopup() {
        const card = document.getElementById('popupCard');
        popup.classList.remove('is-visible');
        document.body.classList.remove('announcement-popup-open');

        if (!alwaysShow) {
          setCookie(cookieName, 'dismissed', cookieDays);
        }

        setTimeout(() => {
          popup.hidden = true;
          if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
          }
        }, 260);
      };

      popup.addEventListener('click', (event) => {
        if (event.target === popup) {
          window.closePopup();
        }
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && popup.classList.contains('is-visible')) {
          window.closePopup();
        }
      });

      window.addEventListener('DOMContentLoaded', () => {
        const hasSeen = getCookie(cookieName) === 'dismissed';
        if (!hasSeen || alwaysShow) {
          setTimeout(openPopup, 900);
        }
      });
    })();
  </script>
  @endif

  <script>
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Animate on scroll
    const observerOptions = {
      threshold: 0.1
    };
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
        }
      });
    }, observerOptions);

    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
  </script>
  <!-- ───────────────────────────────────── ADMISSION GUIDE MODAL ───────────────────────────────────── -->
  <div id="guide-modal" class="modal-overlay">
    <div class="modal-container">
      <div class="modal-header">
        <h2 class="modal-title">Admission Guidelines</h2>
        <div class="flex items-center gap-4">
          <button id="print-guide" class="modal-close" aria-label="Print guidelines">
            <i data-iconsax="printer"></i>
          </button>
          <button id="close-guide" class="modal-close" aria-label="Close modal">
            <i data-iconsax="x"></i>
          </button>
        </div>
      </div>
      <div class="modal-body">

        <!-- NEW STUDENTS -->
        <section class="guide-section">
          <h3 class="guide-category">
            <i data-iconsax="user-plus"></i>
            New Students (Freshmen – Graduate & Undergraduate)
          </h3>
          <div class="guide-list">
            <div class="guide-item">
              <span class="guide-step">1</span>
              <p class="guide-text">Fill out the admission form and submit to the Admission Office.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">2</span>
              <div class="guide-text">
                Present the following documents in a <strong>long brown envelope</strong>.
                <span class="guide-note">Note: Write your full name in CAPITAL LETTERS at the upper left corner (SURNAME, FIRST NAME, MIDDLE NAME).</span>
                <div class="guide-sub-list">
                  <span class="guide-sub-item">3pcs 2X2 picture with white background</span>
                  <span class="guide-sub-item">Original Card of Grade 11 and 12 (Report Card)</span>
                  <span class="guide-sub-item">Original Good Moral Certificate</span>
                  <span class="guide-sub-item">Photocopy of PSA Birth Certificate</span>
                  <span class="guide-sub-item">Photocopy of SHS Diploma</span>
                </div>
              </div>
            </div>
            <div class="guide-item">
              <span class="guide-step">3</span>
              <p class="guide-text">Take the Entrance Exam; Mental Ability Test and Aptitude Test.
                <span class="guide-note">(Professional Exam required for Accountancy and Education Enrollees)</span>
              </p>
            </div>
            <div class="guide-item">
              <span class="guide-step">4</span>
              <p class="guide-text">Fill out the enrollment form.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">5</span>
              <p class="guide-text">Proceed to the Program Director for interview/evaluation.</p>
            </div>
          </div>
        </section>

        <!-- TRANSFEREE -->
        <section class="guide-section">
          <h3 class="guide-category">
            <i data-iconsax="refresh-cw"></i>
            Transferees
          </h3>
          <div class="guide-list">
            <div class="guide-item">
              <span class="guide-step">1</span>
              <p class="guide-text">Fill out the admission form and submit to the Admission Office.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">2</span>
              <div class="guide-text">
                Present the following documents in a <strong>long brown envelope</strong>.
                <span class="guide-note">Note: Write your full name in CAPITAL LETTERS at the upper left corner (SURNAME, FIRST NAME, MIDDLE NAME).</span>
                <div class="guide-sub-list">
                  <span class="guide-sub-item">3pcs 2X2 picture with white background</span>
                  <span class="guide-sub-item">Original Transcript of Records (TOR)</span>
                  <span class="guide-sub-item">Honorable Dismissal</span>
                  <span class="guide-sub-item">Original Good Moral Certificate</span>
                  <span class="guide-sub-item">Photocopy of PSA Birth Certificate</span>
                </div>
              </div>
            </div>
            <div class="guide-item">
              <span class="guide-step">3</span>
              <p class="guide-text">Take the Entrance Exam; Mental Ability Test and Aptitude Test.
                <span class="guide-note">(Professional Exam required for Accountancy and Education Enrollees)</span>
              </p>
            </div>
            <div class="guide-item">
              <span class="guide-step">4</span>
              <p class="guide-text">Fill out the enrollment form.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">5</span>
              <p class="guide-text">Proceed to the Program Director for interview/evaluation.</p>
            </div>
          </div>
        </section>

        <!-- RETURNING STUDENTS -->
        <section class="guide-section">
          <h3 class="guide-category">
            <i data-iconsax="rotate-ccw"></i>
            Returning Students
          </h3>
          <div class="guide-list">
            <div class="guide-item">
              <span class="guide-step">1</span>
              <p class="guide-text">Secure Clearance for enrollment from the Registrar's Office.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">2</span>
              <p class="guide-text">Present the accomplished Clearance to the Admission Office for issuance of the Enrollment Form.</p>
            </div>
          </div>
        </section>

        <!-- ALS GRADUATES -->
        <section class="guide-section">
          <h3 class="guide-category">
            <i data-iconsax="graduation-cap"></i>
            ALS Graduates
          </h3>
          <div class="guide-list">
            <div class="guide-item">
              <span class="guide-step">1</span>
              <p class="guide-text">Fill out the admission form and submit to the Admission Office.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">2</span>
              <div class="guide-text">
                Present the following documents in a <strong>long brown envelope</strong>.
                <span class="guide-note">Note: Write your full name in CAPITAL LETTERS at the upper left corner (SURNAME, FIRST NAME, MIDDLE NAME).</span>
                <div class="guide-sub-list">
                  <span class="guide-sub-item">3pcs 2X2 picture with white background</span>
                  <span class="guide-sub-item">ALS Certificate of Completion (Original)</span>
                  <span class="guide-sub-item">PEPT / TPEP Certificate (if applicable)</span>
                  <span class="guide-sub-item">Original Good Moral Certificate</span>
                  <span class="guide-sub-item">Photocopy of PSA Birth Certificate</span>
                </div>
              </div>
            </div>
            <div class="guide-item">
              <span class="guide-step">3</span>
              <p class="guide-text">Take the Entrance Exam; Mental Ability Test and Aptitude Test.
                <span class="guide-note">(Professional Exam required for Accountancy and Education Enrollees)</span>
              </p>
            </div>
            <div class="guide-item">
              <span class="guide-step">4</span>
              <p class="guide-text">Fill out the enrollment form.</p>
            </div>
            <div class="guide-item">
              <span class="guide-step">5</span>
              <p class="guide-text">Proceed to the Program Director for interview/evaluation.</p>
            </div>
          </div>
        </section>

      </div>
    </div>
  </div>

  <script src="{{ asset('js/home-page.js') }}?v=9"></script>
  <script>
    // Initialize all icons including those in the modal and footer
    if (window.iconsax) {
      iconsax.createIcons();
    }
  </script>
</body>

</html>
