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

<div
  id="announcementPopup"
  data-id="{{ $popupAnn->id }}"
  class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 font-poppins bg-[#050c16]/70 backdrop-blur-[14px] backdrop-saturate-[1.3] opacity-0 invisible pointer-events-none transition-all duration-300 ease-out"
  role="dialog"
  aria-modal="true"
  aria-label="Announcement"
  aria-describedby="announcementPopupMessage"
  aria-hidden="true"
  hidden>

  <div
    id="popupCard"
    tabindex="-1"
    class="relative flex w-full max-w-[1060px] max-h-[min(92vh,680px)] flex-col overflow-hidden rounded-[32px] border border-[#0A1F44]/[0.06] bg-white shadow-[0_40px_120px_rgba(4,10,22,0.28)] outline-none opacity-0 scale-[0.96] translate-y-5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

    <div class="grid min-h-0 flex-1 grid-cols-1 items-stretch md:grid-cols-[58fr_42fr]">
      {{-- LEFT: content --}}
      <div class="flex min-h-0 flex-col gap-3 overflow-y-auto px-6 py-7 sm:px-8 sm:py-8 lg:px-9 lg:py-9 lg:pr-5">
        <div class="flex items-center gap-3">
          <img
            src="{{ asset('assets/images/logo_v2.png') }}"
            alt="BTECH Logo"
            class="h-11 w-11 shrink-0 object-contain"
            onerror="this.remove()">
          <div class="min-w-0 leading-tight">
            <p class="text-[10.5px] font-bold uppercase tracking-wide text-[#0A1F44] sm:text-[11px]">DALUBHASAANG POLYTECHNIC COLLEGE</p>
            <p class="text-[9.5px] font-medium text-[#8a97ad]">Excellence &bull; Innovation &bull; Service</p>
          </div>
        </div>

        <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-[#F4B942] bg-[#FFFBEB] px-3 py-1 text-[9.5px] font-bold uppercase tracking-[0.08em] text-[#D97706]">
          <i data-iconsax="notification" class="h-3.5 w-3.5 shrink-0"></i>
          IMPORTANT ANNOUNCEMENT
        </span>

        <div>
          @if($titleIsWelcome)
          <h2 class="text-[clamp(1.45rem,2.2vw,1.95rem)] font-bold leading-[1.28] tracking-tight text-[#0A1F44]">
            Welcome to the<br>
            <span class="font-extrabold">BTECH Admission Website!</span>
          </h2>
          @else
          <h2 class="text-[clamp(1.45rem,2.2vw,1.95rem)] font-bold leading-[1.28] tracking-tight text-[#0A1F44]">{{ $popupTitle }}</h2>
          @endif
          <span class="mt-2 block h-1 w-[88px] rounded-sm bg-[#F4B942]" aria-hidden="true"></span>
        </div>

        <p id="announcementPopupMessage" class="text-[13px] leading-[1.62] text-[#5f6f85]">{{ $popupMessage }}</p>

        @if($showOnboardingExtras)
        <div class="mt-1 grid grid-cols-2 gap-2.5 md:grid-cols-4 md:gap-3">
          <div class="flex h-full min-h-[100px] gap-2.5 rounded-xl border border-[#edf1f6] bg-white p-3 shadow-[0_4px_18px_rgba(10,31,68,0.06)] transition-shadow duration-200 hover:shadow-[0_8px_24px_rgba(10,31,68,0.1)]">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6effa] text-[#0A1F44]">
              <i data-iconsax="monitor" class="h-[17px] w-[17px]"></i>
            </div>
            <div class="min-w-0">
              <h4 class="text-[11px] font-bold leading-tight text-[#0A1F44]">Easy Access</h4>
              <p class="mt-0.5 text-[9px] leading-[1.45] text-[#6d7c92]">All admission information in one convenient place.</p>
            </div>
          </div>

          <div class="flex h-full min-h-[100px] gap-2.5 rounded-xl border border-[#edf1f6] bg-white p-3 shadow-[0_4px_18px_rgba(10,31,68,0.06)] transition-shadow duration-200 hover:shadow-[0_8px_24px_rgba(10,31,68,0.1)]">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6effa] text-[#0A1F44]">
              <i data-iconsax="document-text" class="h-[17px] w-[17px]"></i>
            </div>
            <div class="min-w-0">
              <h4 class="text-[11px] font-bold leading-tight text-[#0A1F44]">Simple Process</h4>
              <p class="mt-0.5 text-[9px] leading-[1.45] text-[#6d7c92]">Step-by-step guidance for a smooth application.</p>
            </div>
          </div>

          <div class="flex h-full min-h-[100px] gap-2.5 rounded-xl border border-[#edf1f6] bg-white p-3 shadow-[0_4px_18px_rgba(10,31,68,0.06)] transition-shadow duration-200 hover:shadow-[0_8px_24px_rgba(10,31,68,0.1)]">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6effa] text-[#0A1F44]">
              <i data-iconsax="notification" class="h-[17px] w-[17px]"></i>
            </div>
            <div class="min-w-0">
              <h4 class="text-[11px] font-bold leading-tight text-[#0A1F44]">Stay Updated</h4>
              <p class="mt-0.5 text-[9px] leading-[1.45] text-[#6d7c92]">Get the latest announcements and reminders.</p>
            </div>
          </div>

          <div class="flex h-full min-h-[100px] gap-2.5 rounded-xl border border-[#edf1f6] bg-white p-3 shadow-[0_4px_18px_rgba(10,31,68,0.06)] transition-shadow duration-200 hover:shadow-[0_8px_24px_rgba(10,31,68,0.1)]">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6effa] text-[#0A1F44]">
              <i data-iconsax="shield-tick" class="h-[17px] w-[17px]"></i>
            </div>
            <div class="min-w-0">
              <h4 class="text-[11px] font-bold leading-tight text-[#0A1F44]">Secure &amp; Trusted</h4>
              <p class="mt-0.5 text-[9px] leading-[1.45] text-[#6d7c92]">Your data is safe with our secure admission system.</p>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-3 rounded-xl border border-[#f5e8b8] bg-[#FFFBEB] px-4 py-3.5">
          <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#f5e6b8] to-[#e8d48a] text-[#9a7b1a] shadow-[0_2px_8px_rgba(244,185,66,0.25)]">
            <i data-iconsax="shield-tick" class="h-4 w-4"></i>
          </div>
          <p class="text-[12.5px] leading-[1.5] text-[#0A1F44]">
            <strong class="font-bold">Start your future with confidence.</strong>
            We're here to support you every step of the way.
          </p>
        </div>
        @endif
      </div>

      {{-- RIGHT: hero banner --}}
      <div class="relative min-h-[220px] overflow-hidden bg-gradient-to-br from-[#0A1F44] via-[#123d7a] to-[#1e5fad] sm:min-h-[260px] md:min-h-full md:self-stretch">
        <img
          src="{{ asset('assets/images/announcement_popup.png') }}"
          alt=""
          class="absolute inset-0 h-[118%] w-full -top-[12%] object-cover object-[52%_58%]"
          loading="lazy"
          decoding="async">
        <div
          class="pointer-events-none absolute inset-y-0 left-0 z-[2] w-[48%] bg-gradient-to-r from-white via-white/55 to-transparent"
          aria-hidden="true"></div>
        <button
          type="button"
          onclick="closeAnnouncementPopup()"
          class="absolute right-4 top-4 z-30 flex h-8 w-8 items-center justify-center rounded-full bg-[#0A1F44] text-lg font-semibold leading-none text-white shadow-[0_4px_14px_rgba(4,10,22,0.25)] transition-colors duration-200 hover:bg-[#152d52]"
          aria-label="Close modal">&times;</button>
      </div>
    </div>

    <footer class="flex shrink-0 flex-col gap-4 border-t border-[#e8edf4] bg-white px-6 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-9 sm:py-5">
      <label class="inline-flex cursor-pointer select-none items-center gap-2.5">
        <input
          type="checkbox"
          id="dontShowAgain"
          class="h-[15px] w-[15px] rounded border-[#c5d0de] accent-[#0A1F44]">
        <span class="text-xs font-medium text-[#6b7a90]">Don't show this again</span>
      </label>

      <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center sm:gap-2.5">
        @if($popupAnn->popup_button_link)
        <a
          href="{{ $popupAnn->popup_button_link }}"
          class="inline-flex min-h-[42px] min-w-[148px] items-center justify-center rounded-[9px] border border-[#d5dde8] bg-white px-5 text-[12.5px] font-semibold text-[#0A1F44] transition-all duration-200 hover:border-[#b8c5d6] hover:bg-[#f8fafc]">
          {{ $popupAnn->popup_button_text ?? 'View Announcements' }}
        </a>
        @else
        <a
          href="{{ route('news-events') }}"
          onclick="closeAnnouncementPopup()"
          class="inline-flex min-h-[42px] min-w-[148px] items-center justify-center rounded-[9px] border border-[#d5dde8] bg-white px-5 text-[12.5px] font-semibold text-[#0A1F44] transition-all duration-200 hover:border-[#b8c5d6] hover:bg-[#f8fafc]">
          View Announcements
        </a>
        @endif

        <a
          href="{{ route('home') }}?fresh=true"
          onclick="closeAnnouncementPopup()"
          class="inline-flex min-h-[42px] min-w-[148px] items-center justify-center rounded-[9px] bg-[#0A1F44] px-5 text-[12.5px] font-semibold text-white shadow-[0_6px_18px_rgba(10,31,68,0.22)] transition-all duration-200 hover:bg-[#152d52] hover:shadow-[0_8px_22px_rgba(10,31,68,0.28)]">
          Get Started
        </a>
      </div>
    </footer>
  </div>
</div>

<script src="{{ asset('js/announcement-popup.js') }}?v=1" defer></script>
@endif
