<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Frequently Asked Questions — BTECH Admission Office</title>
  <meta name="description" content="Find answers to commonly asked questions about Baliwag Polytechnic College admissions, requirements, and enrollment.">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=27" />
  <style>
.faq-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .faq-item {
      background: #fff;
      border: 1px solid rgba(148, 163, 184, .2);
      border-radius: 16px;
      margin-bottom: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(15, 30, 61, 0.04);
    }

    .faq-item:hover {
      border-color: rgba(27, 53, 87, 0.3);
      box-shadow: 0 8px 25px rgba(15, 30, 61, 0.08);
    }

    .faq-trigger {
      width: 100%;
      padding: 24px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-align: left;
      background: none;
      border: none;
      cursor: pointer;
      font-weight: 700;
      color: #1b3557;
      font-size: 1.05rem;
    }

    .faq-icon {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #f1f5f9;
      color: #1b3557;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .faq-item.active .faq-icon {
      background: #071b3d;
      color: #fff;
      transform: rotate(180deg);
    }

    .faq-content {
      max-height: 0;
      overflow: hidden;
      transition: all 0.3s cubic-bezier(0, 1, 0, 1);
      background: #fff;
    }

    .faq-item.active .faq-content {
      max-height: 1000px;
      transition: all 0.3s cubic-bezier(1, 0, 1, 0);
    }

    .faq-body {
      padding: 0 28px 24px;
      color: #475569;
      font-size: 0.95rem;
      line-height: 1.7;
    }

    .faq-category-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #1b3557;
      margin-bottom: 20px;
      margin-top: 40px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .faq-category-title:first-child {
      margin-top: 0;
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="52" height="52" decoding="async" onerror="this.remove()"></div>
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
            <span class="text-xs font-semibold tracking-widest uppercase">Support Center</span>
          </div>
          <h1 class="hero-headline" data-animate="fade-up" data-delay="100">
            <span class="block text-line-1">Frequently Asked</span>
            <span class="block text-line-2 italic">Questions</span>
          </h1>
          <p class="hero-sub mt-4 text-lg leading-relaxed max-w-2xl" data-animate="fade-up" data-delay="200">
            Everything you need to know about BTECH admissions. If you can't find your answer here, please contact us.
          </p>
        </div>
      </div>
    </section>

    <section class="py-28">
      <div class="max-w-7xl mx-auto px-8">
        <div class="faq-container">

          <h3 class="faq-category-title"><i data-iconsax="edit" style="width:24px;height:24px;"></i> General Admission</h3>
          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>When is the admission period for the next academic year?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">The admission period typically starts around February or March for the first semester. Exact dates are announced on our official website and Facebook page.</div>
            </div>
          </div>

          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>Are there any age limits for applicants?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">BTECH welcomes applicants of all ages who meet the academic and document requirements for their chosen program.</div>
            </div>
          </div>

          <h3 class="faq-category-title"><i data-iconsax="document" style="width:24px;height:24px;"></i> Requirements & Process</h3>
          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>Can I apply if I haven't graduated Senior High School yet?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">Yes, you can begin the application process. However, your official enrollment will be conditional until you submit your complete SHS graduation credentials.</div>
            </div>
          </div>

          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>Is there an entrance examination?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">Yes, BTECH conducts an entrance examination for all new applicants. The exam helps evaluate your academic preparedness for your chosen program.</div>
            </div>
          </div>

          <h3 class="faq-category-title"><i data-iconsax="money" style="width:24px;height:24px;"></i> Tuition & Scholarships</h3>
          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>Is the tuition really free at BTECH?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">Yes, under RA 10931 (Universal Access to Quality Tertiary Education), tuition and other school fees are free for qualified Filipino students. Some miscellaneous fees may still apply.</div>
            </div>
          </div>

          <div class="faq-item" data-animate="fade-up">
            <button class="faq-trigger">
              <span>How do I apply for the Tertiary Education Subsidy (TES)?</span>
              <div class="faq-icon"><i data-iconsax="arrow-down" style="width:14px;height:14px;"></i></div>
            </button>
            <div class="faq-content">
              <div class="faq-body">TES application is handled by CHED and UniFAST. BTECH assists in the submission of names of enrolled students. Eligibility is determined based on national criteria.</div>
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
            <h2 class="apply-cta-title">Still have questions?<br><em>Contact Us.</em></h2>
            <p class="apply-cta-sub mt-4 max-w-xl">Our team is ready to help you with any specific concerns or inquiries you may have.</p>
          </div>
          <div class="flex flex-col gap-4 min-w-64">
            <a href="{{ route('home') }}#contact" class="btn-cta-ghost">Message Admissions <i data-iconsax="arrow-right"></i></a>
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

    // Simple FAQ Accordion Logic
    document.querySelectorAll('.faq-trigger').forEach(trigger => {
      trigger.addEventListener('click', () => {
        const item = trigger.parentElement;
        const isActive = item.classList.contains('active');

        // Close all other items
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));

        // Toggle current item
        if (!isActive) {
          item.classList.add('active');
        }
      });
    });
  </script>
</body>

</html>