@php
/**
 * Header / Navbar partial
 */
@endphp
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
      <a href="{{ route('news-events') }}" class="nav-link text-sm font-medium tracking-wide">News &amp; Events</a>
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
        <a href="{{ route('news-events') }}" class="mobile-nav-link" style="--i:4"><i data-iconsax="notification"></i><span>News &amp; Events</span></a>
        <a href="{{ route('home') }}#contact" class="mobile-nav-link" style="--i:5"><i data-iconsax="message"></i><span>Contact Us</span></a>
      </nav>
      <div class="mobile-menu-footer" style="--i:6">
        <a href="{{ route('apply') }}" class="mobile-btn-primary"><span>Inquire Now</span><i data-iconsax="arrow-right"></i></a>
      </div>
    </div>
  </div>
</header>
