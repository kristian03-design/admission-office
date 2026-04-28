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
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=2" />

  <style>
  /* ── News/Events detail page: force solid navbar since there's no dark hero ── */
  #navbar {
    background: rgba(27, 53, 87, 0.97) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 2px 24px rgba(0, 0, 0, .18);
  }

  /* Keep the same scrolled-state styles active from the start */
  #navbar .nav-sub  { color: rgba(255, 255, 255, .7)  !important; }
  #navbar .nav-main { color: var(--white)              !important; }
  #navbar .nav-link { color: rgba(255, 255, 255, .75)  !important; }
  #navbar .nav-link:hover { color: var(--white)        !important; }

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

  <!-- ───────────────────────────────────── NEWS EVENT DETAILS ───────────────────────────────────── --> 
  <main>
  <section class="pt-32 pb-20" style="background: var(--gray-50);">
    <div style="max-width: 860px; margin: 0 auto; padding: 0 2rem;">

      <a href="{{ route('news-events') }}"
         style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:var(--navy-mid);text-decoration:none;margin-bottom:2rem;">
        <i data-lucide="chevron-left" style="width:15px;height:15px;"></i>
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
                      onclick="setMainImage({{ $index }})"
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
          <span class="meta-item"><i data-lucide="calendar-days"></i> {{ $item->event_date->format('M d, Y') }}</span>
        @endif
        @if($item->location)
          <span class="meta-item"><i data-lucide="map-pin"></i> {{ $item->location }}</span>
        @endif
      </div>

      <h1 style="font-size:2rem;font-weight:600;line-height:1.25;margin-top:1.25rem;font-family:'Cormorant Garamond',Georgia,serif;color:var(--navy-dark);">
        {{ $item->title }}
      </h1>

      @if($item->summary)
        <p style="font-size:1.05rem;line-height:1.75;margin-top:1rem;color:var(--gray-600);">
          {{ $item->summary }}
        </p>
      @endif

      @if($item->content)
        <div style="height:1px;background:rgba(0,0,0,.08);margin:1.75rem 0;"></div>
        <div style="font-size:.95rem;color:var(--gray-600);line-height:1.9;white-space:pre-line;">
          {{ $item->content }}
        </div>
      @endif

    </div>
  </section>
</main>

@if(count($gallery))
  <div id="imageViewer" class="image-viewer" role="dialog" aria-modal="true" aria-label="Image viewer" onclick="handleViewerBackdrop(event)">
    <button type="button" class="image-viewer-btn image-viewer-close" onclick="closeImageViewer()" aria-label="Close image viewer">
      <i data-lucide="x" style="width:22px;height:22px;"></i>
    </button>
    @if(count($gallery) > 1)
      <button type="button" class="image-viewer-btn image-viewer-nav image-viewer-prev" onclick="showViewerImage(currentGalleryIndex - 1)" aria-label="Previous image">
        <i data-lucide="chevron-left" style="width:24px;height:24px;"></i>
      </button>
    @endif
    <img id="imageViewerImage" src="{{ $gallery[0] }}" alt="{{ $item->title }}" decoding="async">
    @if(count($gallery) > 1)
      <button type="button" class="image-viewer-btn image-viewer-nav image-viewer-next" onclick="showViewerImage(currentGalleryIndex + 1)" aria-label="Next image">
        <i data-lucide="chevron-right" style="width:24px;height:24px;"></i>
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

  <script src="{{ asset('js/home-page.js') }}?v=2"></script>
  <script>
    const detailGallery = @json(array_values($gallery));
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

    if (window.lucide) lucide.createIcons();
  </script>
</body>
</html>
