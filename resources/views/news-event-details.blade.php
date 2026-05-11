<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $item->title }} — News & Events</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" style="border-radius:50%;width:32px;height:32px;"/>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}?v=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=12" />

  <style>
  /* ── News/Events detail page: force solid navbar since there's no dark hero ── */
  #navbar, #navbar.scrolled {
    background: rgba(27, 53, 87, 0.97) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 2px 24px rgba(0, 0, 0, .18);
  }

  /* Force light text regardless of scroll state */
  #navbar .nav-sub, #navbar.scrolled .nav-sub   { color: rgba(255, 255, 255, .7)  !important; }
  #navbar .nav-main, #navbar.scrolled .nav-main  { color: #ffffff                  !important; }
  #navbar .nav-link, #navbar.scrolled .nav-link  { color: rgba(255, 255, 255, .75) !important; }
  #navbar .nav-link:hover, #navbar.scrolled .nav-link:hover { color: #ffffff       !important; }

  /* ── Fix mobile menu toggle icon color ── */
  #menu-toggle { color: var(--white); }

  .detail-image-button {
    display: block;
    width: 100%;
    border: 0;
    padding: 0;
    background: transparent;
    cursor: zoom-in;
  }

  .detail-image-button:focus-visible,
  .detail-thumb-button:focus-visible,
  .image-viewer-btn:focus-visible {
    outline: 3px solid rgba(37, 99, 235, .55);
    outline-offset: 3px;
  }

  .image-viewer {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(8, 16, 32, .88);
  }

  .image-viewer.is-open {
    display: flex;
  }

  .image-viewer img {
    max-width: min(1120px, 92vw);
    max-height: 82vh;
    object-fit: contain;
    border-radius: 14px;
    box-shadow: 0 24px 80px rgba(0, 0, 0, .45);
  }

  .image-viewer-btn {
    border: 1px solid rgba(255,255,255,.22);
    background: rgba(255,255,255,.12);
    color: #fff;
    cursor: pointer;
    transition: background .2s ease, transform .2s ease;
  }

  .image-viewer-btn:hover {
    background: rgba(255,255,255,.2);
    transform: translateY(-1px);
  }

  .image-viewer-close,
  .image-viewer-nav {
    position: absolute;
    width: 44px;
    height: 44px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .image-viewer-close {
    top: 18px;
    right: 18px;
  }

  .image-viewer-nav {
    top: 50%;
  }

  .image-viewer-prev { left: 18px; }
  .image-viewer-next { right: 18px; }

  @media (max-width: 640px) {
    .image-viewer {
      padding: 16px;
    }

    .image-viewer img {
      max-width: 96vw;
      max-height: 76vh;
    }

    .image-viewer-nav {
      top: auto;
      bottom: 18px;
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

  <!-- ───────────────────────────────────── NEWS EVENT DETAILS ───────────────────────────────────── --> 
  <main>
  @php
    $gallery = array_map(fn($url) => str_starts_with($url, 'http') ? $url : asset(ltrim(str_replace('/storage/', 'storage/', $url), '/')), $gallery);
  @endphp
  <section id="gallery-section" class="pt-32 pb-20" style="background: var(--gray-50);" data-gallery="{{ json_encode(array_values($gallery)) }}">
    <div style="max-width: 860px; margin: 0 auto; padding: 0 2rem;">

      <a href="{{ route('news-events') }}"
         style="display:inline-flex;align-items:center;gap:6px;font-size:15px;font-weight:600;color:var(--navy-mid);text-decoration:none;margin-bottom:2rem;">
        <i data-iconsax="chevron-left" style="width:17px;height:17px;"></i>
        Back to News &amp; Events
      </a>

      @if(count($gallery))
        <button type="button" class="detail-image-button" onclick="openImageViewer(currentGalleryIndex)" aria-label="View full image">
          <img id="detailMainImage"
               src="{{ $gallery[0] }}"
               alt="{{ $item->title }}"
               fetchpriority="high"
               decoding="async"
               style="width:100%;height:420px;object-fit:cover;border-radius:20px;display:block;">
        </button>

        @if(count($gallery) > 1)
          <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
            @foreach($gallery as $index => $img)
              <button type="button"
                      class="detail-thumb-button"
                      data-index="{{ $index }}" onclick="setMainImage(parseInt(this.getAttribute('data-index')))"
                      style="width:72px;height:72px;border-radius:12px;overflow:hidden;border:none;cursor:pointer;padding:0;opacity:.8;transition:opacity .2s;"
                      onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='.8'"
                      aria-label="Select image {{ $index + 1 }}">
                <img src="{{ $img }}" alt="" loading="lazy" decoding="async" width="72" height="72" style="width:100%;height:100%;object-fit:cover;">
              </button>
            @endforeach
          </div>
        @endif
      @endif

      <div style="display:flex;flex-wrap:wrap;align-items:center;gap:10px;margin-top:2rem;">
        <span class="program-badge {{ ($item->type ?? '') === 'event' ? 'badge--hosp' : 'badge--biz' }}">
          {{ strtoupper($item->type ?? 'news') }}
        </span>
        @if($item->event_date)
          <span class="meta-item"><i data-iconsax="calendar-days"></i> {{ $item->event_date->format('M d, Y') }}</span>
        @endif
        @if($item->location)
          <span class="meta-item"><i data-iconsax="map-pin"></i> {{ $item->location }}</span>
        @endif
      </div>

      <h1 style="font-size:2.8rem;font-weight:700;line-height:1.2;margin-top:1.25rem;font-family:'Cormorant Garamond',Georgia,serif;color:var(--navy-dark);">
        {{ $item->title }}
      </h1>

      @if($item->summary)
        <p style="font-size:1.25rem;line-height:1.75;margin-top:1.25rem;color:var(--gray-600);font-weight:500;">
          {{ $item->summary }}
        </p>
      @endif

      @if($item->content)
        <div style="height:1px;background:rgba(0,0,0,.08);margin:2.5rem 0;"></div>
        <div style="font-size:1.1rem;color:var(--gray-600);line-height:2;white-space:pre-line;">
          {{ $item->content }}
        </div>
      @endif

    </div>
  </section>
</main>

@if(count($gallery))
  <div id="imageViewer" class="image-viewer" role="dialog" aria-modal="true" aria-label="Image viewer" onclick="handleViewerBackdrop(event)">
    <button type="button" class="image-viewer-btn image-viewer-close" onclick="closeImageViewer()" aria-label="Close image viewer">
      <i data-iconsax="x" style="width:22px;height:22px;"></i>
    </button>
    @if(count($gallery) > 1)
      <button type="button" class="image-viewer-btn image-viewer-nav image-viewer-prev" onclick="showViewerImage(currentGalleryIndex - 1)" aria-label="Previous image">
        <svg class="nav-arrow-icon" style="width:24px;height:24px;" viewBox="0 0 24 24">
          <path d="M15 6 9 12l6 6" />
        </svg>
      </button>
    @endif
    <img id="imageViewerImage" src="{{ $gallery[0] }}" alt="{{ $item->title }}" decoding="async">
    @if(count($gallery) > 1)
      <button type="button" class="image-viewer-btn image-viewer-nav image-viewer-next" onclick="showViewerImage(currentGalleryIndex + 1)" aria-label="Next image">
        <svg class="nav-arrow-icon" style="width:24px;height:24px;" viewBox="0 0 24 24">
          <path d="m9 6 6 6-6 6" />
        </svg>
      </button>
    @endif
  </div>
@endif

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

  <script src="{{ asset('js/home-page.js') }}?v=5"></script>
  <script>
    const detailGallery = JSON.parse(document.getElementById('gallery-section').getAttribute('data-gallery') || '[]');
    let currentGalleryIndex = 0;

    function setMainImage(index) {
      if (!detailGallery.length) return;
      currentGalleryIndex = (index + detailGallery.length) % detailGallery.length;
      const img = document.getElementById('detailMainImage');
      if (!img) return;
      img.src = detailGallery[currentGalleryIndex];
    }

    function showViewerImage(index) {
      if (!detailGallery.length) return;
      currentGalleryIndex = (index + detailGallery.length) % detailGallery.length;
      const viewerImage = document.getElementById('imageViewerImage');
      if (viewerImage) viewerImage.src = detailGallery[currentGalleryIndex];
      setMainImage(currentGalleryIndex);
    }

    function openImageViewer(index = 0) {
      const viewer = document.getElementById('imageViewer');
      if (!viewer) return;
      showViewerImage(index);
      viewer.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    }

    function closeImageViewer() {
      const viewer = document.getElementById('imageViewer');
      if (!viewer) return;
      viewer.classList.remove('is-open');
      document.body.style.overflow = '';
    }

    function handleViewerBackdrop(event) {
      if (event.target && event.target.id === 'imageViewer') closeImageViewer();
    }

    document.addEventListener('keydown', function (event) {
      const viewer = document.getElementById('imageViewer');
      if (!viewer || !viewer.classList.contains('is-open')) return;
      if (event.key === 'Escape') closeImageViewer();
      if (event.key === 'ArrowLeft') showViewerImage(currentGalleryIndex - 1);
      if (event.key === 'ArrowRight') showViewerImage(currentGalleryIndex + 1);
    });

    if (window.iconsax) iconsax.createIcons();
  </script>
</body>
</html>


