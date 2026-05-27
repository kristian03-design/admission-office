<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Applicant Portal - BTECH Admissions</title>
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <script>
    window.ICONSAX_SPRITE_PATH = "{{ asset('assets/iconsax-sprite.svg') }}";
    window.SUPABASE_URL = "{{ $supabaseUrl }}";
    window.SUPABASE_ANON_KEY = "{{ $supabaseAnonKey }}";
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_v2.png') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.iconsax')
  <link rel="stylesheet" href="{{ asset('css/home-page.css') }}?v=33" />
  <link rel="stylesheet" href="{{ asset('css/applicant-portal.css') }}?v=8" />
  <style>
    /* Navbar: solid dark navy when unscrolled — this page has a light background */
    #navbar:not(.scrolled) {
      background: #071b3d !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body>
  @include('partials.site-loader')

@include('partials.header')


  <main class="portal-shell">
    @include('partials.applicant-portal.access')
    @include('partials.applicant-portal.help')
    @include('partials.applicant-portal.applicant_dashboard')
  </main>

  @include('partials.footer')
  <div id="portalToast" class="toast"></div>

  <script>
    (function () {
      function hideSiteLoader() {
        const loader = document.getElementById('site-loader');
        if (!loader) return;
        loader.classList.add('is-hidden');
        document.body.classList.remove('site-loader-lock');
        setTimeout(() => loader.remove(), 550);
      }

      document.body.classList.add('site-loader-lock');
      window.addEventListener('load', () => {
        setTimeout(hideSiteLoader, 450);
      });
      setTimeout(hideSiteLoader, 3000);
    })();
  </script>
  <script src="{{ asset('js/api-config.js') }}?v=4"></script>
  <script src="{{ asset('js/applicant-portal.js') }}?v=10"></script>
</body>

</html>
