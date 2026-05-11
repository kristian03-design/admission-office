<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $program->name }} | {{ $settings['institution_name'] ?? 'Baliwag Polytechnic College' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=12" />

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
      <nav class="nav-desktop hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="nav-actions flex items-center gap-3">
        <a href="{{ route('apply') }}" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
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
          <a href="{{ route('apply') }}" class="mobile-btn-primary">
            <span>Inquire Now</span>
            <i data-iconsax="arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </header>
  <!-- FIX: </header> was missing in the original -->

  <!-- Breadcrumb Strip -->
  <div class="breadcrumb-strip">
    <div class="breadcrumb-inner">
      <a href="{{ url('/') }}">Programs</a>
      <span class="sep">/</span>
      <span class="current">{{ $program->department }}</span>
      <span class="sep">/</span>
      <span class="current">{{ $program->name }}</span>
    </div>
  </div>

  <!-- ─── Hero ─── -->
  <section class="program-hero">
    <div class="hero-deco-ring r1"></div>
    <div class="hero-deco-ring r2"></div>
    <div class="hero-deco-line l1"></div>
    <div class="hero-deco-line l2"></div>

    <div class="hero-inner">
      <div class="hero-grid">

        <!-- Left: Program info -->
        <div>
          <div class="hero-dept-badge">
            <span class="dot"></span>
            {{ $program->department }}
          </div>

          <h1 class="hero-title">{{ $program->name }}</h1>

          <p class="hero-desc">
            {{ $program->description ?? 'Empowering students with industry-relevant skills and knowledge through our comprehensive ' . $program->name . ' curriculum. Shaped for today\'s demands, designed for tomorrow\'s leaders.' }}
          </p>

          <div class="hero-meta-row">
            <div class="hero-meta-item">
              <div class="hero-meta-icon"><i data-iconsax="clock"></i></div>
              <div>
                <p class="hero-meta-label">Duration</p>
                <p class="hero-meta-value">4 Years</p>
              </div>
            </div>
            <div class="hero-meta-item">
              <div class="hero-meta-icon"><i data-iconsax="sun-moon"></i></div>
              <div>
                <p class="hero-meta-label">Schedule</p>
                <p class="hero-meta-value">Day / Evening</p>
              </div>
            </div>
            <div class="hero-meta-item">
              <div class="hero-meta-icon"><i data-iconsax="shield-check"></i></div>
              <div>
                <p class="hero-meta-label">Status</p>
                @php
                  $slotsLeft = (int) ($program->slots_left ?? 0);
                  $isOpen = $program->is_active && $slotsLeft > 0;
                @endphp
                <p class="hero-meta-value {{ $isOpen ? 'status-open' : 'status-closed' }}">
                  {{ $isOpen ? 'Admissions Open' : ($slotsLeft <= 0 ? 'Full Slot' : 'Closed') }}
                </p>
              </div>
            </div>
          </div>

          <div class="hero-meta-row" style="margin-top: 1.5rem;">
            <div class="hero-meta-item">
              <div class="hero-meta-icon"><i data-iconsax="users"></i></div>
              <div>
                <p class="hero-meta-label">Slots</p>
                <p class="hero-meta-value">{{ $program->slots_left ?? 'Limited' }} Left</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Apply card -->
        <div>
          <div class="apply-card">
            <p class="apply-card-title">Start Your Journey</p>
            <p class="apply-card-sub">Join the next generation of professionals at Baliwag Polytechnic College. Your career starts here.</p>
            <ul class="apply-checklist">
              <li>
                <span class="check-circle"><i data-iconsax="check"></i></span>
                Fully CHED Accredited
              </li>
              <li>
                <span class="check-circle"><i data-iconsax="check"></i></span>
                Expert Industry Faculty
              </li>
              <li>
                <span class="check-circle"><i data-iconsax="check"></i></span>
                Scholarships Available
              </li>
              <li>
                <span class="check-circle"><i data-iconsax="check"></i></span>
                OJT & Industry Partners
              </li>
            </ul>
            <a href="{{ route('apply') }}" class="btn-apply-card">Apply for this Course</a>
            <div class="apply-divider"></div>
            <div class="apply-card-slots">
              <span>Available Slots</span>
              <span class="slots-count">{{ $program->slots_left ?? '–' }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ─── Highlights Strip ─── -->
  <section class="highlights-section">
    <div class="section-wrap">
      <div class="highlights-grid">
        <div class="highlight-card" data-reveal data-delay="0">
          <div class="highlight-icon"><i data-iconsax="graduation-cap"></i></div>
          <h3 class="highlight-title">CHED Recognized</h3>
          <p class="highlight-desc">Fully accredited and recognized by the Commission on Higher Education.</p>
        </div>
        <div class="highlight-card" data-reveal data-delay="100">
          <div class="highlight-icon"><i data-iconsax="users-round"></i></div>
          <h3 class="highlight-title">Expert Faculty</h3>
          <p class="highlight-desc">Industry practitioners and seasoned academics in every lecture and lab.</p>
        </div>
        <div class="highlight-card" data-reveal data-delay="200">
          <div class="highlight-icon"><i data-iconsax="badge-percent"></i></div>
          <h3 class="highlight-title">Scholarships</h3>
          <p class="highlight-desc">CHED grants and institutional scholarships to keep education accessible.</p>
        </div>
        <div class="highlight-card" data-reveal data-delay="300">
          <div class="highlight-icon"><i data-iconsax="handshake"></i></div>
          <h3 class="highlight-title">Industry Partners</h3>
          <p class="highlight-desc">200+ local and national partner companies for OJT and employment.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ─── Overview + Sidebar ─── -->
  <section id="overview" class="overview-section">
    <div class="section-wrap">
      <div class="overview-grid">

        <!-- Main content -->
        <div>
          <div data-reveal>
            <span class="section-tag">Program Overview</span>
            <h2 class="section-title">What You'll<br><em>Learn & Become</em></h2>
            <div class="section-body">
              <p>
                The <strong>{{ $program->name }}</strong> is designed to produce globally competitive professionals equipped with technical expertise and professional integrity. Our curriculum is constantly reviewed and updated to meet the evolving demands of the industry.
              </p>
              <p>
                Students will undergo intensive training through a combination of theoretical learning, laboratory sessions, and industry immersion. Our state-of-the-art facilities provide a conducive environment for learning and innovation — nurturing graduates who don't just find jobs, they lead industries.
              </p>
            </div>
          </div>

          <div data-reveal data-delay="100">
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--navy);margin-top:2.5rem;margin-bottom:1rem;">
              Core Areas of Study
            </h3>
            <div class="study-areas">
              @if($program->core_areas && count($program->core_areas) > 0)
                @foreach($program->core_areas as $area)
                  <div class="study-area-card">
                    <div class="study-area-icon"><i data-iconsax="{{ $area['icon'] ?? 'book-open' }}"></i></div>
                    <span class="study-area-label">{{ $area['name'] ?? $area }}</span>
                  </div>
                @endforeach
              @else
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="book-open"></i></div>
                  <span class="study-area-label">Professional Ethics</span>
                </div>
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="monitor"></i></div>
                  <span class="study-area-label">Modern Systems</span>
                </div>
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="users"></i></div>
                  <span class="study-area-label">Collaborative Labs</span>
                </div>
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="briefcase"></i></div>
                  <span class="study-area-label">Industry Immersion</span>
                </div>
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="flask-conical"></i></div>
                  <span class="study-area-label">Applied Research</span>
                </div>
                <div class="study-area-card">
                  <div class="study-area-icon"><i data-iconsax="presentation"></i></div>
                  <span class="study-area-label">Leadership & Comm.</span>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div data-reveal data-delay="200">
          <div class="sidebar-card">
            <div class="sidebar-card-header">
              <h3>Career Opportunities</h3>
              <p>Graduates of this program are highly sought after across various industries.</p>
            </div>
            <div class="sidebar-card-body">
              <ul class="career-list">
                @if(!empty($careerOpportunities))
                  @foreach($careerOpportunities as $career)
                    <li>
                      <span class="career-dot"><i data-iconsax="circle-check"></i></span>
                      {{ $career }}
                    </li>
                  @endforeach
                @endif
              </ul>

              <div class="sidebar-divider"></div>

              <p class="sidebar-contact-title">Need Help?</p>
              <p style="font-size:.83rem;color:var(--text-muted);line-height:1.6;margin-bottom:1rem;">Our admissions counselors are ready to assist with any questions.</p>
              <a href="tel:{{ $settings['contact_phone'] ?? '' }}" class="contact-btn">
                <i data-iconsax="phone"></i>
                <span>{{ $settings['contact_phone'] ?? '(044) 766 2222' }}</span>
              </a>
              <a href="mailto:{{ $settings['admissions_email'] ?? '' }}" class="contact-btn">
                <i data-iconsax="mail"></i>
                <span>{{ $settings['admissions_email'] ?? 'admissions@btc.edu.ph' }}</span>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ─── Footer CTA ─── -->
  <section class="footer-cta-section">
    <div class="section-wrap">
      <div class="footer-cta-inner" data-reveal>
        <div class="footer-cta-text">
          <h2 class="footer-cta-title">
            Your Future Begins<br><em>This Enrollment Season.</em>
          </h2>
          <p class="footer-cta-sub">
            Don't wait. Seats are limited and scholarship slots fill quickly. Take the first step toward the career and the life you've been working toward.
          </p>
        </div>
        <div class="footer-cta-actions">
          <a href="{{ url('/') }}#programs" class="btn-cta-ghost">Explore Other Programs <i data-iconsax="arrow-right"></i></a>
        </div>
      </div>
    </div>
  </section>

   <!-- ───────────────────────────────────── FOOTER ───────────────────────────────────── -->
  <footer class="footer-section pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-8">
      <div class="grid md:grid-cols-4 gap-10 pb-12 border-b footer-border">

        <div class="md:col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" class="w-16 h-16 rounded-full object-cover" loading="lazy" decoding="async" width="64" height="64">
            <p class="text-sm font-semibold footer-heading">Baliwag Polytechnic College</p>
          </div>
          <p class="text-sm footer-text leading-relaxed">Empowering Bulacan's future leaders through accessible, quality higher education since 2008.</p>
          <div class="social-links mt-5 flex gap-3">
            <a href="https://www.facebook.com/BTECHAdmissionsOfficial" class="social-btn" aria-label="Facebook">
              <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 8.5V7c0-.7.5-1 1.1-1H17V3h-2.6C11.7 3 10 4.7 10 7.1v1.4H8v3h2V21h3.5v-9.5h2.8l.5-3H13.5Z" fill="currentColor"/></svg>
            </a>
            <a href="https://www.youtube.com/c/BaliwagPolytechnicCollege" class="social-btn" aria-label="Youtube">
              <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M21.6 7.2a3 3 0 0 0-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5a3 3 0 0 0-2.1 2.1A31.1 31.1 0 0 0 2 12a31.1 31.1 0 0 0 .4 4.8 3 3 0 0 0 2.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 0 0 2.1-2.1A31.1 31.1 0 0 0 22 12a31.1 31.1 0 0 0-.4-4.8ZM10 15.2V8.8l5.5 3.2Z" fill="currentColor"/></svg>
            </a>
          </div>
        </div>

        <div>
          <h4 class="footer-col-title mb-4">Programs</h4>
          <ul class="footer-links">
            <li><a href="#">BS Information Technology</a></li>
            <li><a href="#">BS Business Administration</a></li>
            <li><a href="#">BS Secondary Education</a></li>
            <li><a href="#">BS Hospitality Management</a></li>
            <li><a href="#">BS Tourism Management</a></li>
            <li><a href="#">BS Management Accounting</a></li>
            <li><a href="#">Bachelor of Arts in History</a></li>
            <li><a href="#">Bachelor of Science in Mathematics</a></li>
            <li><a href="#">BS Elementary Education</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title mb-4">Admissions</h4>
          <ul class="footer-links">
            <li><a href="#">How to Apply</a></li>
            <li><a href="#">Requirements</a></li>
            <li><a href="#">Scholarship Programs</a></li>
            <li><a href="#">Tuition &amp; Fees</a></li>
            <li><a href="#">FAQs</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title mb-4">Quick Links</h4>
          <ul class="footer-links">
            <li><a href="{{ route('about') }}#about-office">About BTECH Admission</a></li>
            <li><a href="{{ route('about') }}#faculty-staff">Faculty &amp; Staff</a></li>
            <li><a href="{{ route('news-events') }}">News &amp; Events</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>

      </div>

      <div class="footer-bottom flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
        <p class="text-sm footer-text">© 2026 Baliwag Polytechnic College. All rights reserved.</p>
            <div class="flex gap-4 mt-8">
              @if(isset($settings['facebook_link']))
                <a href="{{ $settings['facebook_link'] }}" class="footer-social-link social-btn" aria-label="Facebook">
                  <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 8.5V7c0-.7.5-1 1.1-1H17V3h-2.6C11.7 3 10 4.7 10 7.1v1.4H8v3h2V21h3.5v-9.5h2.8l.5-3H13.5Z" fill="currentColor"/></svg>
                </a>
              @endif
              @if(isset($settings['twitter_link']))
                <a href="{{ $settings['twitter_link'] }}" class="footer-social-link social-btn" aria-label="X">
                  <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="m13.8 10.5 6.4-7.5h-1.5l-5.6 6.5L8.7 3H3.6l6.7 9.8L3.6 21h1.5l5.9-6.9 4.7 6.9h5.1Zm-2.1 2.4-.7-1L5.6 4.1H8l4.4 6.3.7 1 5.7 8.2h-2.4Z" fill="currentColor"/></svg>
                </a>
              @endif
              @if(isset($settings['instagram_link']))
                <a href="{{ $settings['instagram_link'] }}" class="footer-social-link social-btn" aria-label="Instagram">
                  <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4Zm9.8 1.7a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4ZM12 7.2a4.8 4.8 0 1 1 0 9.6 4.8 4.8 0 0 1 0-9.6Zm0 2a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6Z" fill="currentColor"/></svg>
                </a>
              @endif
            </div>
      </div>
    </div>
  </footer>


  <!-- Scripts -->
  <script>
    iconsax.createIcons();

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

    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
    }

    // Reveal on scroll
    const revealEls = document.querySelectorAll('[data-reveal]');
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(el => revealObserver.observe(el));

    // Smooth scroll for hash links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
      });
    });
  </script>

</body>
</html>