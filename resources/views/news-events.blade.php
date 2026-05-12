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
  <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=16" />
  <style>
    /* ── Navbar: dark on load, white on scroll ── */
    /* Un-scrolled: solid navy so text is readable over the light page background */
    #navbar:not(.scrolled) {
      background: rgba(27, 53, 87, 0.98) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 2px 24px rgba(0, 0, 0, .18);
    }
    #navbar:not(.scrolled) .nav-sub   { color: rgba(255, 255, 255, .7)  !important; }
    #navbar:not(.scrolled) .nav-main  { color: #ffffff                  !important; }
    #navbar:not(.scrolled) .nav-link  { color: rgba(255, 255, 255, .75) !important; }
    #navbar:not(.scrolled) .nav-link:hover { color: #ffffff             !important; }
    #navbar:not(.scrolled) #menu-toggle { color: #ffffff; }

    /* Scrolled: let home-page.css handle the white styles — no override here */

    .news-card-media {
      position: relative;
      margin-bottom: 1.1rem;
      aspect-ratio: 16 / 10;
      overflow: hidden;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(27, 53, 87, .08), rgba(201, 147, 58, .14));
    }

    .news-card-media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .25s ease;
    }

    .program-card:hover .news-card-media img {
      transform: scale(1.035);
    }

    .news-card-fallback {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--navy, #1b3557);
    }

    .news-card-fallback i {
      width: 44px;
      height: 44px;
      opacity: .45;
    }

    .news-results-summary {
      color: var(--gray-600, #4b5563);
      font-size: .92rem;
      line-height: 1.6;
    }

    .news-pagination {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      width: 100%;
      padding: 1rem;
      border: 1px solid rgba(27, 53, 87, .08);
      border-radius: 12px;
      background: rgba(255, 255, 255, .82);
      box-shadow: 0 12px 36px rgba(27, 53, 87, .08);
    }

    .news-pagination__pages {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .4rem;
      flex-wrap: wrap;
    }

    .news-page-link,
    .news-page-current,
    .news-page-disabled,
    .news-page-gap {
      min-width: 42px;
      height: 42px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      font-size: .9rem;
      font-weight: 800;
      text-decoration: none;
    }

    .news-page-link {
      border: 1px solid rgba(27, 53, 87, .1);
      color: var(--navy, #1b3557);
      background: #f9fafb;
      transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }

    .news-page-link:hover {
      border-color: rgba(201, 147, 58, .5);
      color: var(--gold, #b8860b);
      transform: translateY(-1px);
    }

    .news-page-current {
      background: var(--navy, #1b3557);
      color: #fff;
      box-shadow: 0 8px 20px rgba(27, 53, 87, .2);
    }

    .news-page-disabled,
    .news-page-gap {
      color: rgba(75, 85, 99, .55);
      background: rgba(240, 242, 245, .85);
    }

    .news-page-link--nav,
    .news-page-disabled--nav {
      min-width: auto;
      padding: 0 1rem;
      gap: .4rem;
      white-space: nowrap;
    }

    .news-page-link i,
    .news-page-disabled i {
      width: 16px;
      height: 16px;
    }

    @media (max-width: 768px) {
      .news-pagination {
        flex-direction: column;
        align-items: stretch;
      }

      .news-pagination__summary {
        text-align: center;
      }

      .news-pagination__pages {
        order: 2;
      }

      .news-page-link--nav,
      .news-page-disabled--nav {
        flex: 1;
      }
    }

    .news-events-grid {
      grid-template-columns: repeat(auto-fill, minmax(300px, 380px));
      justify-content: start;
    }

    .news-events-grid .program-card {
      width: 100%;
      max-width: 380px;
    }

    @media (max-width: 640px) {
      .news-events-grid {
        grid-template-columns: 1fr;
      }

      .news-events-grid .program-card {
        max-width: none;
      }
    }

    .news-events-hero {
      min-height: auto !important;
    }

    .news-events-hero .news-events-hero-inner {
      padding-top: calc(var(--navbar-height, 104px) + 1.5rem) !important;
      padding-bottom: 2.75rem !important;
    }

    @media (max-width: 768px) {
      .news-events-hero .news-events-hero-inner {
        padding-left: 1.25rem !important;
        padding-right: 1.25rem !important;
        padding-top: calc(var(--navbar-height, 86px) + 1.25rem) !important;
        padding-bottom: 2.25rem !important;
      }
    }
  </style>
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

  <main>
    <section class="hero-section subpage-hero news-events-hero relative flex items-center overflow-hidden">
      <div class="hero-bg-overlay absolute top-0 left-0 right-0 bottom-0"></div>
      <div class="hero-pattern absolute top-0 left-0 right-0 bottom-0"></div>
      <div class="news-events-hero-inner relative z-10 max-w-7xl mx-auto px-8 w-full">
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
        <div class="news-results-summary mb-8">
          @if($newsEvents->total() > 0)
            Showing {{ $newsEvents->firstItem() }}-{{ $newsEvents->lastItem() }} of {{ $newsEvents->total() }} updates
          @else
            No published updates yet
          @endif
        </div>

        <div class="programs-grid news-events-grid">
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
                <div class="news-card-media">
                  @if(count($gallery))
                    <img src="{{ $gallery[0] }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                  @else
                    <div class="news-card-fallback" aria-hidden="true">
                      <i data-iconsax="{{ $item->type === 'event' ? 'calendar-days' : 'notification' }}"></i>
                    </div>
                  @endif
                </div>
                <div class="program-badge {{ $item->type === 'event' ? 'badge--hosp' : 'badge--biz' }}">
                  {{ strtoupper($item->type) }}
                </div>
                <h3 class="program-name mt-4">{{ $item->title }}</h3>
                @if($previewText)
                  <p class="program-desc mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($previewText), 170) }}</p>
                @endif
                <div class="program-meta mt-4">
                  @if($item->event_date)
                    <span class="meta-item"><i data-iconsax="calendar-days"></i> {{ $item->event_date->format('M d, Y') }}</span>
                  @endif
                  @if($item->location)
                    <span class="meta-item"><i data-iconsax="map-pin"></i> {{ $item->location }}</span>
                  @endif
                </div>
                <a href="{{ route('news-events.show', $item->id) }}" class="program-cta group">
                  View More <i data-iconsax="chevron-right"></i>
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
          @php
            $currentPage = $newsEvents->currentPage();
            $lastPage = $newsEvents->lastPage();
            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $currentPage + 2);
          @endphp
          <nav class="mt-16 news-pagination" aria-label="News and events pagination">
            <div class="news-pagination__summary news-results-summary">
              Page {{ $newsEvents->currentPage() }} of {{ $newsEvents->lastPage() }}
            </div>

            <div class="news-pagination__pages">
              @if($newsEvents->onFirstPage())
                <span class="news-page-disabled news-page-disabled--nav" aria-disabled="true">
                  <i data-iconsax="chevron-left"></i>
                  Previous
                </span>
              @else
                <a href="{{ $newsEvents->previousPageUrl() }}" class="news-page-link news-page-link--nav" rel="prev">
                  <i data-iconsax="chevron-left"></i>
                  Previous
                </a>
              @endif

              @if($startPage > 1)
                <a href="{{ $newsEvents->url(1) }}" class="news-page-link" aria-label="Go to page 1">1</a>
                @if($startPage > 2)
                  <span class="news-page-gap" aria-hidden="true">...</span>
                @endif
              @endif

              @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                  <span class="news-page-current" aria-current="page">{{ $page }}</span>
                @else
                  <a href="{{ $newsEvents->url($page) }}" class="news-page-link" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                @endif
              @endfor

              @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                  <span class="news-page-gap" aria-hidden="true">...</span>
                @endif
                <a href="{{ $newsEvents->url($lastPage) }}" class="news-page-link" aria-label="Go to page {{ $lastPage }}">{{ $lastPage }}</a>
              @endif

              @if($newsEvents->hasMorePages())
                <a href="{{ $newsEvents->nextPageUrl() }}" class="news-page-link news-page-link--nav" rel="next">
                  Next
                  <i data-iconsax="chevron-right"></i>
                </a>
              @else
                <span class="news-page-disabled news-page-disabled--nav" aria-disabled="true">
                  Next
                  <i data-iconsax="chevron-right"></i>
                </span>
              @endif
            </div>
          </nav>
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

  <script src="{{ asset('js/home-page.js') }}?v=8"></script>
  <script>
    if (window.iconsax) iconsax.createIcons();
  </script>
</body>
</html>
