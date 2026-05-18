<footer id="site-footer" class="footer-section pt-16 pb-8">
  <div class="max-w-7xl mx-auto px-8">
    <div class="grid md:grid-cols-4 gap-10 pb-12 border-b footer-border footer-main-grid">

      <div class="md:col-span-1 footer-brand-col">
        <div class="flex items-center gap-3 mb-4">
          <span class="footer-logo-shell" aria-hidden="true">
            <img src="{{ asset('assets/images/logo_v2.png') }}" alt="" class="footer-logo-mark" loading="lazy" decoding="async" width="64" height="64">
          </span>
          <div class="footer-brand-text">
            <p class="text-sm font-semibold footer-heading">BTECH ADMISSIONS OFFICE</p>
            <p class="footer-brand-sub">Dalubhasaang Politekniko ng Lungsod ng Baliwag</p>
          </div>
        </div>
        <p class="text-sm footer-text leading-relaxed">Empowering Bulacan's future leaders through accessible, quality higher education since 2008.</p>
        <div class="social-links mt-5 flex gap-3">
          <a href="https://www.facebook.com/BTECHAdmissionsOfficial" class="social-btn" aria-label="Facebook">
            <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M14 8.5V7c0-.7.5-1 1.1-1H17V3h-2.6C11.7 3 10 4.7 10 7.1v1.4H8v3h2V21h3.5v-9.5h2.8l.5-3H13.5Z" fill="currentColor" />
            </svg>
          </a>
          <a href="https://www.youtube.com/c/BaliwagPolytechnicCollege" class="social-btn" aria-label="Youtube">
            <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M21.6 7.2a3 3 0 0 0-2.1-2.1C17.7 4.6 12 4.6 12 4.6s-5.7 0-7.5.5a3 3 0 0 0-2.1 2.1A31.1 31.1 0 0 0 2 12a31.1 31.1 0 0 0 .4 4.8 3 3 0 0 0 2.1 2.1c1.8.5 7.5.5 7.5.5s5.7 0 7.5-.5a3 3 0 0 0 2.1-2.1A31.1 31.1 0 0 0 22 12a31.1 31.1 0 0 0-.4-4.8ZM10 15.2V8.8l5.5 3.2Z" fill="currentColor" />
            </svg>
          </a>
        </div>
      </div>

      <div class="footer-programs-col">
        <h4 class="footer-col-title mb-4">Programs</h4>
        <ul class="footer-links footer-program-links">
          @foreach(($footerPrograms ?? $programs ?? collect()) as $footerProgram)
          @php
          $footerProgramHref = filled($footerProgram->id ?? null) ? route('programs.show', ['id' => $footerProgram->id]) : route('home') . '#programs';
          @endphp
          <li><a href="{{ $footerProgramHref }}">{{ $footerProgram->name }}</a></li>
          @endforeach
        </ul>
      </div>

      <div>
        <h4 class="footer-col-title mb-4">Admissions</h4>
        <ul class="footer-links">
          <li><a href="{{ route('how-to-apply') }}">How to Apply</a></li>
          <li><a href="{{ route('requirements') }}">Requirements</a></li>
          <li><a href="{{ route('scholarship-programs') }}">Scholarship Programs</a></li>
          <li><a href="{{ route('tuition-fees') }}">Tuition &amp; Fees</a></li>
          <li><a href="{{ route('faqs') }}">FAQs</a></li>
        </ul>
      </div>

      <div>
        <h4 class="footer-col-title mb-4">Quick Links</h4>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}#about-office">About BTECH Admission</a></li>
          <li><a href="{{ route('about') }}#faculty-staff">Faculty &amp; Staff</a></li>
          <li><a href="{{ route('news-events') }}">News &amp; Events</a></li>
          <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom flex flex-col sm:flex-row items-center justify-between gap-4 pt-8">
      <p class="text-sm footer-text">© 2026 Baliwag Polytechnic College. All rights reserved.</p>
      <div class="flex gap-4">
        @if(isset($settings['facebook_link']))
        <a href="{{ $settings['facebook_link'] }}" class="footer-social-link social-btn" aria-label="Facebook">
          <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M14 8.5V7c0-.7.5-1 1.1-1H17V3h-2.6C11.7 3 10 4.7 10 7.1v1.4H8v3h2V21h3.5v-9.5h2.8l.5-3H13.5Z" fill="currentColor" />
          </svg>
        </a>
        @endif
        @if(isset($settings['twitter_link']))
        <a href="{{ $settings['twitter_link'] }}" class="footer-social-link social-btn" aria-label="X">
          <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="m13.8 10.5 6.4-7.5h-1.5l-5.6 6.5L8.7 3H3.6l6.7 9.8L3.6 21h1.5l5.9-6.9 4.7 6.9h5.1Zm-2.1 2.4-.7-1L5.6 4.1H8l4.4 6.3.7 1 5.7 8.2h-2.4Z" fill="currentColor" />
          </svg>
        </a>
        @endif
        @if(isset($settings['instagram_link']))
        <a href="{{ $settings['instagram_link'] }}" class="footer-social-link social-btn" aria-label="Instagram">
          <svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4Zm9.8 1.7a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4ZM12 7.2a4.8 4.8 0 1 1 0 9.6 4.8 4.8 0 0 1 0-9.6Zm0 2a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6Z" fill="currentColor" />
          </svg>
        </a>
        @endif
      </div>
    </div>
  </div>
</footer>
