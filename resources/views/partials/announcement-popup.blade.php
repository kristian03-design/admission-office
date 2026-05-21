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

{{-- Announcement modal: Tailwind-only, matches BTECH admission popup mockup --}}
<div
  id="announcementPopup"
  data-id="{{ $popupAnn->id }}"
  class="fixed inset-0 z-[9999] flex items-center justify-center p-4 font-poppins sm:p-6 bg-[#050c16]/70 backdrop-blur-[16px] backdrop-saturate-[1.25] opacity-0 invisible pointer-events-none transition-all duration-300 ease-out"
  role="dialog"
  aria-modal="true"
  aria-label="Announcement"
  aria-describedby="announcementPopupMessage"
  aria-hidden="true"
  hidden>

  <div
    id="popupCard"
    tabindex="-1"
    class="relative flex w-full max-w-[1080px] max-h-[min(92vh,700px)] flex-col overflow-hidden rounded-[32px] border border-[#0A1F44]/[0.05] bg-white shadow-[0_40px_120px_rgba(4,10,22,0.3)] outline-none opacity-0 scale-[0.96] translate-y-5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]">

    <div class="grid min-h-0 flex-1 grid-cols-1 md:grid-cols-[58fr_42fr] md:items-stretch">
      {{-- LEFT --}}
      <div class="flex min-h-0 flex-col gap-3.5 overflow-y-auto px-7 py-8 sm:gap-4 sm:px-9 sm:py-9 md:pr-6 lg:px-10 lg:py-10">
        <div class="flex items-center gap-3">
          <img
            src="{{ asset('assets/images/logo_v2.png') }}"
            alt="BTECH Logo"
            class="h-[46px] w-[46px] shrink-0 object-contain"
            onerror="this.remove()">
          <div class="min-w-0 leading-tight">
            <p class="text-[11px] font-bold uppercase tracking-[0.04em] text-[#0A1F44]">DALUBHASAANG POLYTECHNIC COLLEGE</p>
            <p class="mt-0.5 text-[10px] font-medium text-[#8a97ad]">Excellence &bull; Innovation &bull; Service</p>
          </div>
        </div>

        <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-[#F4B942] bg-[#FFFBEB] px-3 py-[5px] text-[9.5px] font-bold uppercase tracking-[0.1em] text-[#D97706]">
          <i data-iconsax="notification" class="h-3.5 w-3.5 shrink-0 text-[#D97706]"></i>
          IMPORTANT ANNOUNCEMENT
        </span>

        <div class="space-y-2">
          @if($titleIsWelcome)
          <h2 class="text-[clamp(1.5rem,2.4vw,2rem)] font-bold leading-[1.22] tracking-tight text-[#0A1F44]">
            Welcome to the<br class="hidden sm:block">
            <span class="font-extrabold sm:whitespace-nowrap">BTECH Admission Website!</span>
          </h2>
          @else
          <h2 class="text-[clamp(1.5rem,2.4vw,2rem)] font-bold leading-[1.22] tracking-tight text-[#0A1F44]">{{ $popupTitle }}</h2>
          @endif
          <span class="block h-1 w-10 rounded-sm bg-[#F4B942]" aria-hidden="true"></span>
        </div>

        <p id="announcementPopupMessage" class="max-w-[98%] text-[13px] leading-[1.65] text-[#5f6f85]">{{ $popupMessage }}</p>

        @if($showOnboardingExtras)
        <div class="mt-0.5 grid grid-cols-2 gap-2.5 sm:gap-3 md:grid-cols-4 md:auto-rows-fr">
          @foreach([
            ['icon' => 'monitor', 'title' => 'Easy Access', 'desc' => 'All admission information in one convenient place.'],
            ['icon' => 'document-text', 'title' => 'Simple Process', 'desc' => 'Step-by-step guidance for a smooth application.'],
            ['icon' => 'notification', 'title' => 'Stay Updated', 'desc' => 'Get the latest announcements and reminders.'],
            ['icon' => 'shield-tick', 'title' => 'Secure & Trusted', 'desc' => 'Your data is safe with our secure admission system.'],
          ] as $feature)
          <div class="flex h-full min-h-[108px] gap-2.5 rounded-xl border border-[#e5e7eb] bg-white p-3 shadow-[0_4px_16px_rgba(10,31,68,0.06)] transition-shadow duration-200 hover:shadow-[0_8px_22px_rgba(10,31,68,0.09)] sm:min-h-[112px] sm:p-3.5">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e8f1fa] text-[#0A1F44]">
              <i data-iconsax="{{ $feature['icon'] }}" class="h-[17px] w-[17px]"></i>
            </div>
            <div class="flex min-w-0 flex-1 flex-col justify-center">
              <h4 class="text-[11.5px] font-bold leading-tight text-[#0A1F44]">{{ $feature['title'] }}</h4>
              <p class="mt-1 text-[9.5px] leading-[1.45] text-[#6d7c92]">{{ $feature['desc'] }}</p>
            </div>
          </div>
          @endforeach
        </div>

        <div class="flex items-center gap-3 rounded-xl border border-[#F4B942]/40 bg-[#FFFBEB] px-4 py-3.5">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[#F4B942]/50 bg-[#F4B942]/20 text-[#0A1F44]">
            <i data-iconsax="shield-tick" class="h-4 w-4 text-[#9a7b1a]"></i>
          </div>
          <p class="text-[12.5px] leading-[1.55] text-[#0A1F44]">
            <strong class="font-bold">Start your future with confidence.</strong>
            We're here to support you every step of the way.
          </p>
        </div>
        @endif
      </div>

      {{-- RIGHT: hero --}}
      <div class="relative min-h-[240px] overflow-hidden bg-gradient-to-br from-[#0A1F44] via-[#0f2d5c] to-[#1a4d8c] sm:min-h-[280px] md:min-h-full">
        <img
          src="{{ asset('assets/images/announcement_popup.png') }}"
          alt=""
          class="absolute inset-0 h-[115%] w-full -top-[10%] object-cover object-[68%_58%] md:object-[72%_55%]"
          loading="lazy"
          decoding="async">
        <div
          class="pointer-events-none absolute inset-y-0 left-0 z-[2] w-[52%] bg-gradient-to-r from-white via-white/60 to-transparent"
          aria-hidden="true"></div>
        <button
          type="button"
          onclick="closeAnnouncementPopup()"
          class="absolute right-4 top-4 z-30 flex h-9 w-9 items-center justify-center rounded-full border-2 border-white/85 bg-white/10 text-lg font-light leading-none text-white backdrop-blur-[6px] transition-all duration-200 hover:border-white hover:bg-white/20"
          aria-label="Close modal">&times;</button>
      </div>
    </div>

    <footer class="flex shrink-0 flex-col gap-4 border-t border-[#e8edf4] bg-white px-7 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-10 sm:py-5">
      <label class="inline-flex cursor-pointer select-none items-center gap-2.5">
        <input
          type="checkbox"
          id="dontShowAgain"
          class="h-4 w-4 rounded border-[#c5d0de] accent-[#0A1F44]">
        <span class="text-xs font-medium text-[#6b7a90]">Don't show this again</span>
      </label>

      <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center sm:gap-3">
        @if($popupAnn->popup_button_link)
        <a
          href="{{ $popupAnn->popup_button_link }}"
          class="inline-flex min-h-[42px] min-w-[152px] items-center justify-center rounded-lg border border-[#0A1F44]/20 bg-white px-5 text-[13px] font-semibold text-[#0A1F44] transition-all duration-200 hover:border-[#0A1F44]/35 hover:bg-[#f8fafc]">
          {{ $popupAnn->popup_button_text ?? 'View Announcements' }}
        </a>
        @else
        <a
          href="{{ route('news-events') }}"
          onclick="closeAnnouncementPopup()"
          class="inline-flex min-h-[42px] min-w-[152px] items-center justify-center rounded-lg border border-[#0A1F44]/20 bg-white px-5 text-[13px] font-semibold text-[#0A1F44] transition-all duration-200 hover:border-[#0A1F44]/35 hover:bg-[#f8fafc]">
          View Announcements
        </a>
        @endif

        <a
          href="{{ route('apply') }}?fresh=true"
          onclick="closeAnnouncementPopup()"
          class="inline-flex min-h-[42px] min-w-[152px] items-center justify-center rounded-lg bg-[#0A1F44] px-5 text-[13px] font-semibold text-white shadow-[0_6px_20px_rgba(10,31,68,0.25)] transition-all duration-200 hover:bg-[#0d2852] hover:shadow-[0_8px_24px_rgba(10,31,68,0.32)]">
          Get Started
        </a>
      </div>
    </footer>
  </div>
</div>

<script src="{{ asset('js/announcement-popup.js') }}?v=2" defer></script>
@endif
