<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $program->name }} | {{ $settings['institution_name'] ?? 'Baliwag Polytechnic College' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=23" />

  <style>
    /* ─── Page-level token overrides to match home-page.css ─── */
    :root {
      --navy: #071b3d;
      --navy-dark: #031024;
      --navy-mid: #0b2d6b;
      --course-dark-bg: #071b3d;
      --gold: #c9933a;
      --gold-light: #dfb36a;
      --gold-pale: #fdf6e3;
      --cream: #f8f6f1;
      --slate-soft: #f0f2f5;
      --text-muted: #4b5563;
      --border: rgba(27, 53, 87, .08);
      --radius-md: 12px;
      --radius-lg: 14px;
      --radius-xl: 16px;
    }

    /* ─── Navbar: Dark at top, White on scroll ─── */
    #navbar:not(.scrolled) {
      background: var(--course-dark-bg) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 2px 24px rgba(0, 0, 0, .18);
    }

    #navbar:not(.scrolled) .nav-sub {
      color: rgba(255, 255, 255, .7) !important;
    }

    #navbar:not(.scrolled) .nav-main {
      color: #ffffff !important;
    }

    #navbar:not(.scrolled) .nav-link {
      color: rgba(255, 255, 255, .75) !important;
    }

    #navbar:not(.scrolled) .nav-link:hover {
      color: #ffffff !important;
    }

    #navbar:not(.scrolled) #menu-toggle {
      color: #ffffff !important;
    }

    #navbar.scrolled {
      background: rgba(255, 255, 255, 0.98) !important;
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 2px 24px rgba(15, 35, 64, .10);
    }

    #navbar.scrolled .nav-link {
      color: var(--navy) !important;
    }

    #navbar.scrolled .nav-main {
      color: var(--navy) !important;
    }

    #navbar.scrolled .nav-sub {
      color: rgba(27, 53, 87, .55) !important;
    }

    #navbar.scrolled #menu-toggle {
      color: var(--navy) !important;
    }

    /* ─── Navbar height spacer (pushes content below fixed navbar) ─── */
    .navbar-spacer {
      height: var(--navbar-height, 104px);
    }

    /* ─── Breadcrumb Strip ─── */
    .breadcrumb-strip {
      background: var(--course-dark-bg);
      padding: .85rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      position: relative;
      z-index: 40;
    }

    .breadcrumb-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .breadcrumb-inner a {
      font-size: .82rem;
      color: rgba(255, 255, 255, .6);
      text-decoration: none;
      transition: color .2s;
    }

    .breadcrumb-inner a:hover {
      color: var(--gold-light);
    }

    .breadcrumb-inner .sep {
      color: rgba(255, 255, 255, .2);
      font-size: .7rem;
    }

    .breadcrumb-inner .current {
      font-size: .82rem;
      color: var(--gold-light);
      font-weight: 500;
    }

    /* ─── Hero ─── */
    .program-hero {
      background:
        linear-gradient(rgba(255, 255, 255, .018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .018) 1px, transparent 1px),
        var(--course-dark-bg);
      background-size: 60px 60px, 60px 60px, 100% 100%;
      position: relative;
      overflow: hidden;
      padding: 4.5rem 0 4rem;
    }

    .program-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: transparent;
    }

    .hero-deco-ring {
      position: absolute;
      border-radius: 50%;
      border: 1px solid rgba(201, 147, 58, .12);
    }

    .hero-deco-ring.r1 {
      width: 600px;
      height: 600px;
      top: -200px;
      right: -150px;
    }

    .hero-deco-ring.r2 {
      width: 350px;
      height: 350px;
      bottom: -100px;
      right: 80px;
      border-color: rgba(201, 147, 58, .08);
    }

    .hero-deco-line {
      position: absolute;
      background: rgba(201, 147, 58, .08);
    }

    .hero-deco-line.l1 {
      width: 1px;
      top: 0;
      bottom: 0;
      left: 38%;
    }

    .hero-deco-line.l2 {
      height: 1px;
      left: 0;
      right: 0;
      bottom: 35%;
    }

    .hero-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
      position: relative;
      z-index: 2;
    }

    .program-hero-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 340px;
      gap: clamp(2rem, 5vw, 4rem);
      align-items: start;
    }

    .hero-copy {
      max-width: 720px;
    }

    .hero-dept-badge {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      background: rgba(201, 147, 58, .15);
      border: 1px solid rgba(201, 147, 58, .3);
      color: var(--gold-light);
      font-size: .85rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      padding: .45rem 1.1rem;
      border-radius: 999px;
      margin-bottom: 1.5rem;
    }

    .hero-dept-badge .dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--gold);
    }

    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(2.3rem, 5.4vw, 4rem);
      font-weight: 800;
      color: #fff;
      line-height: .98;
      letter-spacing: 0;
      text-wrap: balance;
    }

    .hero-title em {
      font-style: italic;
      color: var(--gold-light);
      font-weight: 600;
    }

    .hero-desc {
      margin-top: 1.5rem;
      font-size: 1.05rem;
      line-height: 1.75;
      color: rgba(255, 255, 255, .65);
      max-width: 540px;
    }

    .hero-meta-row {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: .85rem;
      margin-top: 2rem;
    }

    .hero-meta-item {
      display: flex;
      align-items: center;
      gap: .7rem;
      min-width: 0;
      padding: .8rem;
      border: 1px solid rgba(255, 255, 255, .09);
      border-radius: 12px;
      background: rgba(255, 255, 255, .045);
    }

    .hero-meta-icon {
      width: 36px;
      height: 36px;
      border-radius: var(--radius-sm);
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--gold-light);
      flex-shrink: 0;
    }

    .hero-meta-icon svg {
      width: 16px;
      height: 16px;
      display: block;
    }

    .hero-meta-label {
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .4);
    }

    .hero-meta-value {
      font-size: .8rem;
      font-weight: 700;
      color: #fff;
      margin-top: .2rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .status-open {
      color: #4ade80;
    }

    .status-closed {
      color: #f87171;
    }

    .hero-cta-row {
      display: flex;
      gap: 1rem;
      margin-top: 2.5rem;
      flex-wrap: wrap;
    }

    .btn-hero-primary {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: .85rem 2rem;
      background: var(--gold);
      color: #fff;
      font-size: .9rem;
      font-weight: 600;
      border-radius: 999px;
      text-decoration: none;
      transition: background .25s, transform .2s, box-shadow .25s;
      box-shadow: 0 8px 24px rgba(201, 147, 58, .3);
    }

    .btn-hero-primary:hover {
      background: var(--gold-light);
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(201, 147, 58, .4);
    }

    .btn-hero-primary svg {
      width: 17px;
      height: 17px;
    }

    .btn-hero-secondary {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: .85rem 1.8rem;
      background: rgba(255, 255, 255, .07);
      border: 1px solid rgba(255, 255, 255, .15);
      color: rgba(255, 255, 255, .8);
      font-size: .9rem;
      font-weight: 500;
      border-radius: 999px;
      text-decoration: none;
      transition: background .25s, color .25s;
    }

    .btn-hero-secondary:hover {
      background: rgba(255, 255, 255, .12);
      color: #fff;
    }

    .btn-hero-secondary svg {
      width: 16px;
      height: 16px;
      opacity: .6;
    }

    .hero-note {
      display: flex;
      align-items: flex-start;
      gap: .65rem;
      max-width: 560px;
      margin-top: 1.25rem;
      color: rgba(255, 255, 255, .58);
      font-size: .9rem;
      line-height: 1.6;
    }

    .hero-note i {
      width: 18px;
      height: 18px;
      color: var(--gold-light);
      flex-shrink: 0;
      margin-top: .1rem;
    }

    /* ─── Apply Card ─── */
    .apply-card {
      background: rgba(255, 255, 255, .05);
      border: 1px solid rgba(255, 255, 255, .12);
      border-radius: var(--radius-xl);
      padding: 1.5rem;
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }

    .apply-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }

    .apply-card-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.35rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: .4rem;
    }

    .apply-card-sub {
      font-size: .82rem;
      color: rgba(255, 255, 255, .55);
      line-height: 1.55;
      margin-bottom: 1.5rem;
    }

    .apply-checklist {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: .75rem;
      margin-bottom: 1.5rem;
    }

    .apply-checklist li {
      display: flex;
      align-items: flex-start;
      gap: .75rem;
      font-size: .88rem;
      color: rgba(255, 255, 255, .8);
    }

    .check-circle {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: rgba(201, 147, 58, .25);
      border: 1px solid rgba(201, 147, 58, .4);
      color: var(--gold-light);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: .05rem;
    }

    .check-circle svg {
      width: 11px;
      height: 11px;
      display: block;
    }

    .btn-apply-card {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      width: 100%;
      padding: 1rem;
      background: var(--gold);
      color: #fff;
      font-size: .9rem;
      font-weight: 700;
      text-align: center;
      border-radius: var(--radius-md);
      text-decoration: none;
      transition: background .25s, transform .2s, box-shadow .25s;
      box-shadow: 0 6px 20px rgba(201, 147, 58, .35);
    }

    .btn-apply-card:hover {
      background: var(--gold-light);
      transform: translateY(-2px);
    }

    .btn-apply-card i {
      width: 17px;
      height: 17px;
    }

    .apply-divider {
      height: 1px;
      background: rgba(255, 255, 255, .08);
      margin: 1.5rem 0;
    }

    .apply-card-slots {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: .82rem;
      color: rgba(255, 255, 255, .5);
    }

    .slots-count {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--gold-light);
    }

    /* ─── Body Sections ─── */
    .section-wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .overview-section {
      padding: 5rem 0;
    }

    .overview-grid {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 3rem;
      align-items: start;
    }

    .section-tag {
      display: inline-block;
      font-size: .85rem;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--gold);
      padding: .45rem 1.1rem;
      background: var(--gold-pale);
      border-radius: 999px;
      margin-bottom: 1rem;
    }

    .section-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1.9rem, 3vw, 2.6rem);
      font-weight: 700;
      color: var(--navy);
      line-height: 1.1;
    }

    .section-title em {
      font-style: italic;
      color: var(--gold);
    }

    .section-body {
      margin-top: 1.25rem;
      font-size: .97rem;
      line-height: 1.8;
      color: var(--text-muted);
    }

    .section-body p+p {
      margin-top: 1rem;
    }

    .section-body strong {
      color: var(--navy);
      font-weight: 600;
    }

    .overview-lead {
      max-width: 680px;
    }

    .program-section-heading {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.55rem;
      font-weight: 800;
      color: var(--navy);
      margin-top: 2.5rem;
      margin-bottom: 1rem;
      line-height: 1.15;
    }

    .study-areas {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .85rem;
      margin-top: 2rem;
    }

    .study-area-card {
      display: flex;
      align-items: center;
      gap: .9rem;
      background: var(--slate-soft);
      border: 1px solid rgba(15, 30, 61, .06);
      border-radius: 12px;
      padding: .9rem 1.1rem;
      transition: border-color .2s, box-shadow .2s;
    }

    .study-area-card:hover {
      border-color: rgba(201, 147, 58, .25);
      box-shadow: 0 4px 16px rgba(201, 147, 58, .08);
    }

    .study-area-icon {
      width: 38px;
      height: 38px;
      border-radius: var(--radius-sm);
      background: #fff;
      box-shadow: 0 2px 8px rgba(15, 30, 61, .08);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--navy);
      flex-shrink: 0;
    }

    .study-area-icon svg {
      width: 17px;
      height: 17px;
    }

    .study-area-label {
      font-size: .88rem;
      font-weight: 600;
      color: var(--navy);
    }

    .sidebar-card {
      background: #fff;
      border: 1px solid rgba(15, 30, 61, .08);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: 0 4px 32px rgba(15, 30, 61, .06);
      position: sticky;
      top: 96px;
    }

    .sidebar-card-header {
      background: linear-gradient(135deg, #031024 0%, #071b3d 58%, #0b2d6b 100%);
      padding: 1.5rem;
    }

    .sidebar-card-header h3 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.25rem;
      font-weight: 700;
      color: #fff;
    }

    .sidebar-card-header p {
      font-size: .82rem;
      color: rgba(255, 255, 255, .55);
      margin-top: .25rem;
      line-height: 1.5;
    }

    .sidebar-card-body {
      padding: 1.5rem;
    }

    .career-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: .75rem;
    }

    .career-list li {
      display: flex;
      align-items: flex-start;
      gap: .7rem;
      font-size: .88rem;
      color: var(--text-muted);
      line-height: 1.4;
    }

    .career-dot {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: rgba(201, 147, 58, .1);
      border: 1px solid rgba(201, 147, 58, .25);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: .1rem;
    }

    .career-dot svg {
      width: 12px;
      height: 12px;
      color: var(--gold);
    }

    .sidebar-divider {
      height: 1px;
      background: rgba(15, 30, 61, .06);
      margin: 1.25rem 0;
    }

    .sidebar-contact-title {
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--navy);
      opacity: .5;
      margin-bottom: 1rem;
    }

    .contact-btn {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .8rem 1rem;
      border-radius: var(--radius-sm);
      background: var(--slate-soft);
      text-decoration: none;
      transition: background .2s;
      margin-bottom: .6rem;
    }

    .contact-btn:last-child {
      margin-bottom: 0;
    }

    .contact-btn:hover {
      background: var(--gold-pale);
    }

    .contact-btn svg {
      width: 17px;
      height: 17px;
      color: var(--navy);
      flex-shrink: 0;
    }

    .contact-btn span {
      font-size: .85rem;
      font-weight: 600;
      color: var(--navy);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* ─── Highlights Strip ─── */
    .highlights-section {
      padding: 4rem 0;
      background:
        linear-gradient(rgba(255, 255, 255, .018) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .018) 1px, transparent 1px),
        var(--course-dark-bg);
      background-size: 60px 60px, 60px 60px, 100% 100%;
      position: relative;
      overflow: hidden;
    }

    .highlights-section::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 50% 100% at 80% 50%, rgba(201, 147, 58, .08), transparent);
    }

    .highlights-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      position: relative;
      z-index: 1;
    }

    .highlight-card {
      padding: 1.75rem 1.5rem;
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: var(--radius-lg);
      transition: background .25s, border-color .25s;
    }

    .highlight-card:hover {
      background: rgba(255, 255, 255, .07);
      border-color: rgba(201, 147, 58, .2);
    }

    .highlight-icon {
      width: 44px;
      height: 44px;
      border-radius: var(--radius-sm);
      background: rgba(201, 147, 58, .15);
      border: 1px solid rgba(201, 147, 58, .25);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--gold-light);
      margin-bottom: 1.25rem;
    }

    .highlight-icon svg {
      width: 20px;
      height: 20px;
    }

    .highlight-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.1rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: .5rem;
    }

    .highlight-desc {
      font-size: .84rem;
      line-height: 1.65;
      color: rgba(255, 255, 255, .5);
    }

    /* ─── Footer CTA ─── */
    .footer-cta-section {
      padding: 5rem 0;
      background: var(--cream);
      border-top: 1px solid var(--border);
    }

    .footer-cta-inner {
      background: linear-gradient(135deg, #031024 0%, #071b3d 48%, #0b2d6b 100%);
      border-radius: var(--radius-xl);
      position: relative;
      overflow: hidden;
      padding: 4rem 3.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 3rem;
    }

    .footer-cta-inner::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 60% 100% at 100% 50%, rgba(201, 147, 58, .12), transparent);
    }

    .footer-cta-inner::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      border: 1px solid rgba(201, 147, 58, .08);
      top: -150px;
      right: -100px;
    }

    .footer-cta-text {
      position: relative;
      z-index: 1;
    }

    .footer-cta-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 2.8rem;
      font-weight: 700;
      color: #fff;
      line-height: 1.1;
    }

    .footer-cta-title em {
      font-style: italic;
      color: var(--gold-light);
    }

    .footer-cta-sub {
      margin-top: 1rem;
      font-size: .97rem;
      color: rgba(255, 255, 255, .55);
      max-width: 440px;
      line-height: 1.7;
    }

    .footer-cta-actions {
      display: flex;
      flex-direction: column;
      gap: .85rem;
      min-width: 220px;
      position: relative;
      z-index: 1;
    }

    .btn-cta-main {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      padding: 1.05rem 2rem;
      background: var(--gold);
      color: #fff;
      font-size: .95rem;
      font-weight: 700;
      border-radius: 999px;
      text-decoration: none;
      transition: background .25s, transform .2s, box-shadow .25s;
      box-shadow: 0 8px 24px rgba(201, 147, 58, .35);
    }

    .btn-cta-main:hover {
      background: var(--gold-light);
      transform: translateY(-2px);
    }

    .btn-cta-main svg {
      width: 17px;
      height: 17px;
    }


    /* ─── Reveal Animations ─── */
    [data-reveal] {
      opacity: 0;
      transform: translateY(24px);
      transition: opacity .6s ease, transform .6s ease;
    }

    [data-reveal].visible {
      opacity: 1;
      transform: none;
    }

    [data-reveal][data-delay="100"] {
      transition-delay: .1s;
    }

    [data-reveal][data-delay="200"] {
      transition-delay: .2s;
    }

    [data-reveal][data-delay="300"] {
      transition-delay: .3s;
    }

    [data-reveal][data-delay="400"] {
      transition-delay: .4s;
    }

    /* ─── Responsive ─── */
    @media (max-width: 1024px) {
      .program-hero-grid {
        grid-template-columns: 1fr;
        gap: 2.5rem;
      }

      .apply-card {
        max-width: none;
      }

      .hero-meta-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .overview-grid {
        grid-template-columns: 1fr;
      }

      .sidebar-card {
        position: static;
      }

      .highlights-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .footer-cta-inner {
        flex-direction: column;
        text-align: center;
      }

      .footer-cta-sub {
        margin: 1rem auto 0;
      }

      .footer-cta-actions {
        width: 100%;
        max-width: 320px;
        align-self: center;
      }
    }

    @media (max-width: 640px) {
      .breadcrumb-inner {
        padding: 0 1.25rem;
        overflow-x: auto;
        white-space: nowrap;
      }

      .hero-inner,
      .section-wrap {
        padding: 0 1.25rem;
      }

      .program-hero {
        padding: 3.25rem 0 3rem;
      }

      .hero-title {
        font-size: clamp(2.15rem, 13vw, 3.1rem);
      }

      .hero-desc {
        font-size: 1rem;
      }

      .hero-meta-row {
        grid-template-columns: 1fr;
      }

      .hero-cta-row {
        flex-direction: column;
      }

      .btn-hero-primary,
      .btn-hero-secondary {
        justify-content: center;
      }

      .study-areas {
        grid-template-columns: 1fr;
      }

      .highlights-grid {
        grid-template-columns: 1fr;
      }

      .footer-cta-inner {
        padding: 2.5rem 1.5rem;
      }

      .footer-cta-title {
        font-size: 2rem;
      }

      .nav-links {
        display: none;
      }

      .footer-bottom-row {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

  <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="nav-inner flex items-center justify-between px-8 py-4 max-w-7xl mx-auto">
      <a href="{{ route('home') }}" class="flex items-center gap-3 group">
        <div class="logo-badge"><img src="{{ asset('assets/images/logo_v2.png') }}" alt="BTECH Logo" width="40" height="40" decoding="async"></div>
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

  <!-- Pushes page content below the fixed navbar -->
  <div class="navbar-spacer" aria-hidden="true"></div>

  <!-- Breadcrumb Strip -->
  <div class="breadcrumb-strip">
    <div class="breadcrumb-inner">
      <a href="{{ url('/') }}">Programs</a>
      <span class="sep">/</span>
      <span class="current">{{ $program->department ?? 'Programs' }}</span>
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
      <div class="program-hero-grid">

        <!-- Left: Program info -->
        <div class="hero-copy">
          <div class="hero-dept-badge">
            <span class="dot"></span>
            {{ $program->department ?? 'Degree Program' }}
          </div>

          <h1 class="hero-title">{{ $program->name }}</h1>

          <p class="hero-desc">
            {{ $program->description ?? 'Empowering students with industry-relevant skills and knowledge through our comprehensive ' . $program->name . ' curriculum. Shaped for today\'s demands, designed for tomorrow\'s leaders.' }}
          </p>

          <div class="hero-meta-row">
            <div class="hero-meta-item">
              <div class="hero-meta-icon">
                <i data-iconsax="clock"></i>
              </div>
              <div>
                <p class="hero-meta-label">Duration</p>
                <p class="hero-meta-value">{{ $program->duration_years ?? 4 }} Years</p>
              </div>
            </div><!-- /.hero-meta-item -->

            <div class="hero-meta-item">
              <div class="hero-meta-icon">
                <i data-iconsax="calendar-days"></i>
              </div>
              <div>
                <p class="hero-meta-label">Schedule</p>
                @php
                $scheduleParts = preg_split('/[\/,|]+/', (string) ($program->schedule ?? 'Day'));
                $displaySchedule = trim($scheduleParts[0] ?? '') ?: 'Day';
                @endphp
                <p class="hero-meta-value">{{ $displaySchedule }}</p>
              </div>
            </div><!-- /.hero-meta-item -->

            <div class="hero-meta-item">
              <div class="hero-meta-icon">
                <i data-iconsax="shield-check"></i>
              </div>
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
            </div><!-- /.hero-meta-item -->

            <div class="hero-meta-item">
              <div class="hero-meta-icon">
                <i data-iconsax="users-round"></i>
              </div>
              <div>
                <p class="hero-meta-label">Slots</p>
                <p class="hero-meta-value">{{ is_numeric($program->slots_left ?? null) ? number_format($program->slots_left) : 'Limited' }} Left</p>
              </div>
            </div><!-- /.hero-meta-item -->
          </div><!-- /.hero-meta-row -->


          <p class="hero-note">
            <i data-iconsax="info-circle"></i>
            Admission availability is based on active program status and remaining slots. Submit an inquiry early so the Admissions Office can guide your next steps.
          </p>
        </div><!-- /.hero-left -->

        <!-- Right: Apply card -->
        <div>
          <div class="apply-card">
            <p class="apply-card-title">Start Your Journey</p>
            <p class="apply-card-sub">Join the next generation of professionals at Baliwag Polytechnic College. Your career starts here.</p>
            <ul class="apply-checklist">
              <li>
                <span class="check-circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg></span>
                Fully CHED Accredited
              </li>
              <li>
                <span class="check-circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg></span>
                Expert Industry Faculty
              </li>
              <li>
                <span class="check-circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg></span>
                Scholarships Available
              </li>
              <li>
                <span class="check-circle"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                  </svg></span>
                OJT & Industry Partners
              </li>
            </ul>
            <a href="{{ route('apply') }}" class="btn-apply-card">
              {{ $isOpen ? 'Apply for this Course' : 'Ask About Availability' }}
              <i data-iconsax="arrow-right"></i>
            </a>
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
            <div class="section-body overview-lead">
              <p>
                The <strong>{{ $program->name }}</strong> is designed to produce globally competitive professionals equipped with technical expertise and professional integrity. Our curriculum is constantly reviewed and updated to meet the evolving demands of the industry.
              </p>
              <p>
                Students will undergo intensive training through a combination of theoretical learning, laboratory sessions, and industry immersion. Our state-of-the-art facilities provide a conducive environment for learning and innovation — nurturing graduates who don't just find jobs, they lead industries.
              </p>
            </div>
          </div>

          <div data-reveal data-delay="100">
            <h3 class="program-section-heading">
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
        </div><!-- /.main-content -->

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
        </div><!-- /.sidebar -->

      </div><!-- /.overview-grid -->
    </div><!-- /.section-wrap -->
  </section>

  <!-- ─── Footer CTA ─── -->
  <section class="footer-cta-section">
    <div class="section-wrap">
      <div class="footer-cta-inner" data-reveal>
        <div class="footer-cta-text">
          <h2 class="footer-cta-title">
            Your Future Begins<br><em>This Enrollment Season.</em>
          </h2>
        </div>
        <div class="footer-cta-actions">
          <a href="{{ url('/') }}#programs" class="btn-cta-ghost">Explore Other Programs <i data-iconsax="arrow-right"></i></a>
        </div>
      </div>
  </section>

  @include('partials.footer')

  <!-- Scripts -->
  <script>
    // Run after DOM is ready so all data-iconsax elements are present
    document.addEventListener('DOMContentLoaded', () => {
      if (typeof iconsax !== 'undefined') iconsax.createIcons();
    });

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

    // Navbar scroll behaviour
    const navbar = document.getElementById('navbar');
    if (navbar) {
      const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 20);
      window.addEventListener('scroll', onScroll, {
        passive: true
      });
      onScroll(); // run immediately on load
    }

    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('active');
        mobileMenu.classList.toggle('active', isOpen);
        menuToggle.classList.toggle('active', isOpen);
        document.body.classList.toggle('menu-open', isOpen);
        menuToggle.setAttribute('aria-expanded', String(isOpen));
      });

      mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
          mobileMenu.classList.remove('active');
          menuToggle.classList.remove('active');
          document.body.classList.remove('menu-open');
          menuToggle.setAttribute('aria-expanded', 'false');
        });
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
    }, {
      threshold: 0.12
    });
    revealEls.forEach(el => revealObserver.observe(el));

    // Smooth scroll for hash links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>

</body>

</html>
