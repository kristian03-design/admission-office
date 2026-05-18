<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $item->title }} � News & Events</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=32" />

  <style>
    /* -- Navbar: dark on load, white on scroll -- */
    /* Un-scrolled: solid navy so text is readable over light page background */
    /* Scrolled: let home-page.css handle the white styles � no override here */

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
      border: 1px solid rgba(255, 255, 255, .22);
      background: rgba(255, 255, 255, .12);
      color: #fff;
      cursor: pointer;
      transition: background .2s ease, transform .2s ease;
    }

    .image-viewer-btn:hover {
      background: rgba(255, 255, 255, .2);
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

    .image-viewer-prev {
      left: 18px;
    }

    .image-viewer-next {
      right: 18px;
    }

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

  <!-- ------------------------------------- NAV ------------------------------------- -->
  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="74" height="74" decoding="async" onerror="this.remove()"></div>
        <div class="leading-tight">
          <p class="text-xs font-medium tracking-widest uppercase opacity-70 nav-sub">{{ $settings['institution_name'] ?? 'BTECH ADMISSIONS OFFICE' }}</p>
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

  <!-- ------------------------------------- NEWS EVENT DETAILS ------------------------------------- -->
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
            data-index="{{ $index }}"
            onclick="setMainImage(parseInt(this.getAttribute('data-index')))"
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
      <svg style="width:24px;height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 6 9 12l6 6" />
      </svg>
    </button>
    @endif
    <img id="imageViewerImage" src="{{ $gallery[0] }}" alt="{{ $item->title }}" decoding="async">
    @if(count($gallery) > 1)
    <button type="button" class="image-viewer-btn image-viewer-nav image-viewer-next" onclick="showViewerImage(currentGalleryIndex + 1)" aria-label="Next image">
      <svg style="width:24px;height:24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m9 6 6 6-6 6" />
      </svg>
    </button>
    @endif
  </div>
  @endif

  <!-- ------------------------------------- FOOTER ------------------------------------- -->
  @include('partials.footer')

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

    document.addEventListener('keydown', function(event) {
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