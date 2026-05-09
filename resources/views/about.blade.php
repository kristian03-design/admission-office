<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About BTECH Admission Office â€” Baliwag Polytechnic College</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=5" />
  <style>
    .team-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 24px;
    }

    .team-card {
      position: relative;
      min-height: 100%;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .28);
      border-radius: 18px;
      background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
      box-shadow: 0 18px 45px rgba(15, 30, 61, .07);
      transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .team-card::before {
      content: "";
      position: absolute;
      inset: 0 0 auto;
      height: 5px;
      background: linear-gradient(90deg, #1b3557, #d99a22);
    }

    .team-card:hover {
      transform: translateY(-4px);
      border-color: rgba(27, 53, 87, .22);
      box-shadow: 0 24px 60px rgba(15, 30, 61, .11);
    }

    .team-card-inner {
      display: flex;
      flex-direction: column;
      height: 100%;
      padding: 28px;
    }

    .team-card-header {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 22px;
    }

    .team-photo-wrap {
      position: relative;
      flex: 0 0 auto;
    }

    .team-photo,
    .team-fallback {
      width: 88px;
      height: 88px;
      border-radius: 22px;
      border: 4px solid #fff;
      box-shadow: 0 12px 24px rgba(15, 30, 61, .16);
    }

    .team-photo {
      object-fit: cover;
      display: block;
      background: #e2e8f0;
    }

    .team-fallback {
      display: none;
      align-items: center;
      justify-content: center;
      background: #1b3557;
      color: #fff;
      font-size: 1.35rem;
      font-weight: 700;
    }

    .team-role {
      display: inline-flex;
      width: fit-content;
      max-width: 100%;
      border-radius: 999px;
      background: rgba(217, 154, 34, .12);
      color: #a36b00;
      padding: 6px 12px;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      line-height: 1.2;
    }

    .team-name {
      margin-top: 12px;
      color: var(--navy-dark);
      font-family: 'Cormorant Garamond', Georgia, serif;
      font-size: 1.45rem;
      font-weight: 700;
      line-height: 1.15;
    }

    .team-note {
      margin-top: 12px;
      color: var(--gray-600);
      font-size: .95rem;
      line-height: 1.75;
    }

    .team-footer {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: auto;
      padding-top: 22px;
      color: #64748b;
      font-size: .82rem;
      font-weight: 600;
    }

    .team-footer i {
      width: 17px;
      height: 17px;
      color: #1b3557;
    }

    @media (max-width: 1024px) {
      .team-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 640px) {
      .team-grid {
        grid-template-columns: 1fr;
      }

      .team-card-inner {
        padding: 24px;
      }

      .team-photo,
      .team-fallback {
        width: 76px;
        height: 76px;
        border-radius: 19px;
      }
    }
  </style>
</head>
<body>
  @include('partials.site-loader')

 <!-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ NAV â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
 <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" width="40" height="40" decoding="async"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSION OFFICE' }}</p>
          <p class="text-base font-semibold tracking-wide nav-main">Dalubhasaang Politekniko ng Lungsod ng Baliwag</p>
        </div>
      </a>
      <nav class="hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <a href="{{ route('apply') }}" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
        <button id="menu-toggle" class="md:hidden p-2 rounded-lg" aria-label="Toggle menu"><i data-iconsax="menu"></i></button>
      </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden mobile-menu">
      <div class="px-8 pb-6 pt-2 flex flex-col gap-4">
        <a href="{{ route('home') }}" class="mobile-nav-link text-base font-medium">Home</a>
        <a href="{{ route('about') }}" class="mobile-nav-link text-base font-medium">About</a>
        <a href="{{ route('news-events') }}" class="mobile-nav-link text-base font-medium">News &amp; Events</a>
      </div>
    </div>
  </header>

  <main>
    <section class="hero-section relative min-h-[70vh] flex items-center overflow-hidden">
      <div class="hero-bg-overlay absolute inset-0"></div>
      <div class="hero-pattern absolute inset-0"></div>
      <div class="relative z-10 max-w-7xl mx-auto px-8 w-full pt-32 pb-16">
        <div class="max-w-3xl">
          <div class="inline-flex items-center gap-2 pill-badge mb-8" data-animate="fade-up">
            <span class="pill-dot"></span>
            <span class="text-xs font-semibold tracking-widest uppercase">Admissions Support &amp; Guidance</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">About BTECH</span>
            <span class="block text-line-2 italic">Admission Office</span>
            <span class="block text-line-3">&amp; Faculty Team</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            The Admissions Office and our faculty and staff work together to support every applicant from first inquiry to enrollment.
          </p>
          <div class="flex flex-wrap gap-4 mt-10" data-animate="fade-up" data-delay="300">
            <a href="#about-office" class="btn-hero-primary">
              <span>Learn About the Office</span>
              <i data-iconsax="arrow-right"></i>
            </a>
            <a href="#faculty-staff" class="btn-hero-secondary">
              <span>Meet Faculty &amp; Staff</span>
              <i data-iconsax="users-round"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <section id="about-office" class="why-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="section-header text-center mb-20" data-animate="fade-up">
          <span class="section-tag">About The Office</span>
          <h2 class="section-title mt-3">BTECH Admission Office</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">
            We provide responsive, student-centered admissions services to help applicants choose the right program and complete requirements smoothly.
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
          <div class="feature-card feature-card--accent" data-animate="fade-up">
            <div class="feature-icon-wrap"><i data-iconsax="target"></i></div>
            <h3 class="feature-title mt-5">Our Mission</h3>
            <p class="feature-desc mt-2">BTECH is committed to providing new academic programs for the youth, as well as delivering quality education through teachers and modern equipment that contribute to the development of the communities it serves.</p>
          </div>
          <div class="feature-card" data-animate="fade-up" data-delay="80">
            <div class="feature-icon-wrap"><i data-iconsax="eye"></i></div>
            <h3 class="feature-title mt-5">Our Vision</h3>
            <p class="feature-desc mt-2">A leading institution of higher education that offers quality education to the youth from various backgrounds, producing knowledgeable, morally upright, and globally competitive individuals in a rapidly changing world.</p>
          </div>
          <div class="feature-card feature-card--dark" data-animate="fade-up" data-delay="160">
            <div class="feature-icon-wrap feature-icon-wrap--light"><i data-iconsax="shield-check"></i></div>
            <h3 class="feature-title feature-title--light mt-5">Core Values</h3>
            <p class="feature-desc feature-desc--light mt-2">BTECH is guided by its core values of discipline, cultural awareness, integrity, patriotism, creativity, excellence, and compassion in shaping responsible and empowered individuals.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="faculty-staff" class="programs-section py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="section-header text-center mb-16" data-animate="fade-up">
          <span class="section-tag">People Behind Admissions</span>
          <h2 class="section-title mt-3">Faculty &amp; Staff</h2>
          <p class="section-desc mt-4 max-w-2xl mx-auto">
            Our team is composed of caring educators and dedicated professionals committed to helping you every step of the way.
          </p>
        </div>

        <div class="team-grid">
          @forelse($team as $index => $member)
            <article class="team-card" data-animate="fade-up" data-delay="{{ ($index % 6) * 70 }}">
              <div class="team-card-inner">
                <div class="team-card-header">
                  <div class="team-photo-wrap">
                    @php
                      $memberImage = $member['image'] ?? '';
                      $memberImageUrl = $memberImage
                        ? (str_starts_with($memberImage, 'http') ? $memberImage : asset(str_starts_with($memberImage, 'storage/') || str_starts_with($memberImage, '/storage/') ? ltrim($memberImage, '/') : 'storage/' . $memberImage))
                        : '';
                    @endphp
                    @if($memberImageUrl)
                      <img
                        src="{{ $memberImageUrl }}"
                        alt="{{ $member['name'] }}"
                        class="team-photo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                      >
                    @endif
                  <div
                    class="team-fallback"
                    style="{{ $memberImageUrl ? '' : 'display:flex;' }}"
                    aria-label="{{ $member['name'] }}"
                  >
                    {{ strtoupper(substr($member['name'], 0, 1)) }}
                  </div>
                  </div>
                  <div>
                    <div class="team-role">{{ $member['role'] }}</div>
                    <h3 class="team-name">{{ $member['name'] }}</h3>
                  </div>
                </div>
                <p class="team-note">{{ $member['note'] }}</p>
                <div class="team-footer">
                  <i data-iconsax="{{ $member['icon'] ?? 'user-round' }}"></i>
                  <span>BTECH Admissions Team</span>
                </div>
              </div>
            </article>
          @empty
            <div class="feature-card" style="grid-column:1/-1;">
              <h3 class="feature-title">Faculty &amp; staff list is being updated.</h3>
              <p class="feature-desc mt-2">Please check back soon.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>

    <section class="apply-cta-section py-28">
      <div class="apply-cta-inner max-w-7xl mx-auto px-8 relative overflow-hidden rounded-3xl">
        <div class="apply-cta-bg"></div>
        <div class="apply-cta-pattern"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12 py-20 px-8">
          <div class="text-center lg:text-left">
            <h2 class="apply-cta-title">Need Help With Your<br/><em>Admission Requirements?</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Reach out to our team and we will guide you on program selection, document preparation, and enrollment timelines.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('apply') }}" class="btn-cta-primary text-center">
              Start Your Application
              <i data-iconsax="arrow-right" style="display:inline-flex;margin-left:.5rem;vertical-align:middle;width:18px;height:18px;"></i>
            </a>
            <a href="{{ route('home') }}#contact" class="btn-cta-link text-center text-sm w-full">Contact Admissions Team â†’</a>
          </div>
        </div>
      </div>
    </section>
  </main>

 <!-- â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ FOOTER â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
 <footer class="footer-section pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-8">
      <div class="grid md:grid-cols-4 gap-10 pb-12 border-b footer-border">

        <div class="md:col-span-1">
          <div class="flex items-center gap-3 mb-4">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="BTECH Logo" class="w-16 h-16 rounded-full object-cover">
            <p class="text-sm font-semibold footer-heading">Baliwag Polytechnic College</p>
          </div>
          <p class="text-sm footer-text leading-relaxed">Empowering Bulacan's future leaders through accessible, quality higher education since 2008.</p>
          <div class="social-links mt-5 flex gap-3">
            <a href="https://www.facebook.com/BTECHAdmissionsOfficial" class="social-btn" aria-label="Facebook"><i data-iconsax="facebook"></i></a>
            <a href="https://www.youtube.com/c/BaliwagPolytechnicCollege" class="social-btn" aria-label="Youtube"><i data-iconsax="youtube"></i></a>
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
        <p class="text-sm footer-text">Â© 2026 Baliwag Polytechnic College. All rights reserved.</p>
            <div class="flex gap-4 mt-8">
              @if(isset($settings['facebook_link']))
                <a href="{{ $settings['facebook_link'] }}" class="footer-social-link" aria-label="Facebook"><i data-iconsax="facebook"></i></a>
              @endif
              @if(isset($settings['twitter_link']))
                <a href="{{ $settings['twitter_link'] }}" class="footer-social-link"><i data-iconsax="twitter"></i></a>
              @endif
              @if(isset($settings['instagram_link']))
                <a href="{{ $settings['instagram_link'] }}" class="footer-social-link"><i data-iconsax="instagram"></i></a>
              @endif
            </div>
      </div>
    </div>
  </footer>

  <button id="back-to-top" class="back-to-top" aria-label="Back to top">
    <i data-iconsax="arrow-up"></i>
  </button>

  <script src="{{ asset('js/home-page.js') }}?v=5"></script>
  <script>
    if (window.iconsax) {
      iconsax.createIcons();
    }
  </script>
</body>
</html>


