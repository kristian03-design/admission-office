<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>News & Events — BTECH Admission Office</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=4" />
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
      <nav class="hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}" class="nav-link text-sm font-medium tracking-wide">Home</a>
        <a href="{{ route('about') }}" class="nav-link text-sm font-medium tracking-wide">About</a>
        <a href="{{ route('home') }}#programs" class="nav-link text-sm font-medium tracking-wide">Programs</a>
        <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
        <a href="{{ route('home') }}#contact" class="nav-link text-sm font-medium tracking-wide">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <a href="{{ route('apply') }}" class="btn-primary-nav text-sm font-semibold px-5 py-2 rounded-full transition-all">Inquire Now</a>
        <button id="menu-toggle" class="md:hidden p-2 rounded-lg" aria-label="Toggle menu"><i data-lucide="menu"></i></button>
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
    <section class="hero-section relative min-h-[55vh] flex items-center overflow-hidden">
      <div class="hero-bg-overlay absolute inset-0"></div>
      <div class="hero-pattern absolute inset-0"></div>
      <div class="relative z-10 max-w-7xl mx-auto px-8 w-full pt-32 pb-16">
        <div class="max-w-3xl">
          <span class="section-tag">Stay Updated</span>
          <h1 class="hero-headline mt-4">
            <span class="block text-line-1">BTECH Admission</span>
            <span class="block text-line-2 italic">News &amp; Events</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl">
            Get the latest announcements, important schedules, and campus activities from the Admissions Office.
          </p>
        </div>
      </div>
    </section>

    <section class="programs-section py-24">
      <div class="max-w-7xl mx-auto px-8">
        <div class="programs-grid">
          @forelse($newsEvents as $item)
            @php
              $galleryUrls = is_array($item->image_urls) && count($item->image_urls)
                ? $item->image_urls
                : ($item->image_url ? [$item->image_url] : []);
              $gallery = array_map(fn($url) => str_starts_with($url, 'http') ? $url : asset(ltrim(str_replace('/storage/', 'storage/', $url), '/')), $galleryUrls);
              $previewText = $item->summary ?: $item->content ?: '';
            @endphp
            <article class="program-card">
              <div class="program-card-inner">
                @if(count($gallery))
                  <img src="{{ $gallery[0] }}" alt="{{ $item->title }}" class="w-full h-44 object-cover rounded-lg mb-4" loading="lazy" decoding="async">
                @endif
                <div class="program-badge {{ $item->type === 'event' ? 'badge--hosp' : 'badge--biz' }}">
                  {{ strtoupper($item->type) }}
                </div>
                <h3 class="program-name mt-4">{{ $item->title }}</h3>
                @if($previewText)
                  <p class="program-desc mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($previewText), 170) }}</p>
                @endif
                <div class="program-meta mt-4">
                  @if($item->event_date)
                    <span class="meta-item"><i data-lucide="calendar-days"></i> {{ $item->event_date->format('M d, Y') }}</span>
                  @endif
                  @if($item->location)
                    <span class="meta-item"><i data-lucide="map-pin"></i> {{ $item->location }}</span>
                  @endif
                </div>
                <a href="{{ route('news-events.show', $item->id) }}" class="program-cta group">
                  View More <i data-lucide="chevron-right"></i>
                </a>
              </div>
            </article>
          @empty
            <div class="feature-card" style="grid-column: 1 / -1;">
              <h3 class="feature-title">No news or events yet.</h3>
              <p class="feature-desc mt-2">Please check back soon for updates.</p>
            </div>
          @endforelse
        </div>

        @if($newsEvents->hasPages())
          <div class="mt-12">
            {{ $newsEvents->links() }}
          </div>
        @endif
      </div>
    </section>
  </main>

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
            <!-- Facebook brand icon — not available in Lucide; inline SVG only -->
            <a href="https://www.facebook.com/BTECHAdmissionsOfficial" class="social-btn" aria-label="Facebook">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <!-- Youtube Brand Icon -->
            <a href="https://www.youtube.com/c/BaliwagPolytechnicCollege" class="social-btn" aria-label="Youtube">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
              </svg>
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
                <a href="{{ $settings['facebook_link'] }}" class="footer-social-link" aria-label="Facebook">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
                </a>
              @endif
              @if(isset($settings['twitter_link']))
                <a href="{{ $settings['twitter_link'] }}" class="footer-social-link"><i data-lucide="twitter"></i></a>
              @endif
              @if(isset($settings['instagram_link']))
                <a href="{{ $settings['instagram_link'] }}" class="footer-social-link"><i data-lucide="instagram"></i></a>
              @endif
            </div>
      </div>
    </div>
  </footer>

  <script src="{{ asset('js/home-page.js') }}?v=5"></script>
  <script>
    if (window.lucide) lucide.createIcons();
  </script>
</body>
</html>
