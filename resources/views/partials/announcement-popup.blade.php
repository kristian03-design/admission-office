@php
  $popupAnn = $popupAnn ?? ($announcements ?? collect())->firstWhere('is_popup', true);
@endphp

@if($popupAnn)
@php
  $popupTitle = filled(trim($popupAnn->title ?? ''))
    ? $popupAnn->title
    : 'Welcome to the BTECH Admission Website!';

  $defaultPopupMessage = "We've made it easier than ever to start your journey. Explore programs, check requirements, and begin your application with just a few clicks.";

  $titleIsWelcome = str_contains(strtolower($popupTitle), 'welcome')
    && str_contains(strtolower($popupTitle), 'btech');

  $popupMessage = $titleIsWelcome
    ? $defaultPopupMessage
    : (filled(trim($popupAnn->message ?? '')) ? $popupAnn->message : $defaultPopupMessage);

  $showOnboardingExtras = $titleIsWelcome
    || !filled(trim($popupAnn->message ?? ''))
    || str_contains(strtolower($popupAnn->title ?? ''), 'welcome');
@endphp

<link rel="stylesheet" href="{{ asset('css/announcement-popup.css') }}?v=10">

<div
  id="announcementPopup"
  data-id="{{ $popupAnn->id }}"
  class="announcement-popup"
  role="dialog"
  aria-modal="true"
  aria-label="Announcement"
  aria-describedby="announcementPopupMessage"
  aria-hidden="true"
  hidden>

  <div id="popupCard" class="announcement-popup__card" tabindex="-1">

    <div class="announcement-popup__body">

      <div class="announcement-popup__content">
        <div class="announcement-popup__brand">
          <img
            src="{{ asset('assets/images/logo_v2.png') }}"
            alt="BTECH Logo"
            class="announcement-popup__logo"
            onerror="this.remove()">

          <div class="announcement-popup__brand-text">
            <p class="announcement-popup__college-name">
              DALUBHASAANG POLYTECHNIC COLLEGE
            </p>
            <p class="announcement-popup__college-motto">
              Excellence &bull; Innovation &bull; Service
            </p>
          </div>
        </div>

        <span class="announcement-popup__badge">
          <i data-iconsax="notification"></i>
          IMPORTANT ANNOUNCEMENT
        </span>

        <div class="announcement-popup__headline-wrap">
          @if($titleIsWelcome)
            <h2 class="announcement-popup__title">
              Welcome to the<br>
              <span class="announcement-popup__title-line2">
                BTECH Admission Website!
              </span>
            </h2>
          @else
            <h2 class="announcement-popup__title">{{ $popupTitle }}</h2>
          @endif

          <span class="announcement-popup__title-accent" aria-hidden="true"></span>
        </div>

        <p id="announcementPopupMessage" class="announcement-popup__message">
          {{ $popupMessage }}
        </p>
      </div>

      <div class="announcement-popup__hero" aria-hidden="true">
        <img
          src="{{ asset('assets/images/announcement_popup.png') }}"
          alt=""
          class="announcement-popup__hero-image"
          loading="lazy"
          decoding="async">

        <div class="announcement-popup__hero-fade" aria-hidden="true"></div>
      </div>

      <button
        type="button"
        class="announcement-popup__close"
        aria-label="Close modal">
        &times;
      </button>

    </div>

    @if($showOnboardingExtras)
      <div class="announcement-popup__features">
        @foreach([
          ['icon' => 'monitor', 'title' => 'Easy Access', 'desc' => 'All admission information in one convenient place.'],
          ['icon' => 'document-text', 'title' => 'Simple Process', 'desc' => 'Step-by-step guidance for a smooth application.'],
          ['icon' => 'notification', 'title' => 'Stay Updated', 'desc' => 'Get the latest announcements and reminders.'],
          ['icon' => 'shield-tick', 'title' => 'Secure & Trusted', 'desc' => 'Your data is safe with our secure admission system.'],
        ] as $feature)
          <div class="announcement-popup__feature-card">
            <div class="announcement-popup__feature-icon">
              <i data-iconsax="{{ $feature['icon'] }}"></i>
            </div>

            <div class="announcement-popup__feature-body">
              <h4 class="announcement-popup__feature-title">
                {{ $feature['title'] }}
              </h4>
              <p class="announcement-popup__feature-desc">
                {{ $feature['desc'] }}
              </p>
            </div>
          </div>
        @endforeach
      </div>

      <div class="announcement-popup__alert">
        <div class="announcement-popup__alert-icon">
          <i data-iconsax="shield-tick"></i>
        </div>

        <p class="announcement-popup__alert-text">
          <strong>Start your future with confidence.</strong>
          We're here to support you every step of the way.
        </p>
      </div>
    @endif

    <footer class="announcement-popup__footer">
      <label class="announcement-popup__dont-show">
        <input type="checkbox" id="dontShowAgain">
        <span>Don't show this again</span>
      </label>

      <div class="announcement-popup__actions">
        @if($popupAnn->popup_button_link)
          <a
            href="{{ $popupAnn->popup_button_link }}"
            class="announcement-popup__btn announcement-popup__btn--secondary">
            {{ $popupAnn->popup_button_text ?? 'View Announcements' }}
          </a>
        @else
          <a
            href="{{ route('news-events') }}"
            class="announcement-popup__btn announcement-popup__btn--secondary"
            data-announcement-close-nav>
            View Announcements
          </a>
        @endif

        <a
          href="{{ route('apply') }}?fresh=true"
          class="announcement-popup__btn announcement-popup__btn--primary"
          data-announcement-close-nav>
          Get Started
        </a>
      </div>
    </footer>

  </div>
</div>

<script src="{{ asset('js/announcement-popup.js') }}?v=10" defer></script>
@endif