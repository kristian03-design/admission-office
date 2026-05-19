<section id="portalAccess" class="portal-hero">
  <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-[1.05fr_.95fr] gap-10 portal-hero-grid">
    <div class="flex flex-col justify-center">
      <p class="text-xs font-bold uppercase tracking-[.18em] text-amber-200 mb-3">BTECH Admissions</p>
      <h1 class="text-5xl md:text-6xl font-semibold leading-tight">Applicant Portal</h1>
      <p class="mt-4 text-white/80 max-w-2xl leading-relaxed">Check your status, update allowed details, upload missing documents, and review your interview schedule using your reference number and submitted email.</p>
    </div>

    <div class="portal-card portal-login-card">
      <div id="lookupStep" class="portal-step active">
        <div class="portal-login-header">
          <span class="portal-login-mark"><i data-iconsax="document-text"></i></span>
          <div>
            <h2 class="portal-login-title">Open your application</h2>
            <p class="portal-login-copy">Enter the details from your submitted application.</p>
          </div>
        </div>
        <form id="lookupForm" class="portal-login-form">
          <div>
            <label class="portal-field-label">Reference Number</label>
            <input class="portal-input" name="reference_number" placeholder="BTECH-2026-000123" required>
          </div>
          <div>
            <label class="portal-field-label">Submitted Email</label>
            <input class="portal-input" type="email" name="email" placeholder="you@example.com" required>
          </div>
          <button id="sendOtpBtn" class="portal-btn portal-btn-primary w-full" type="submit" data-idle-text="Send OTP" data-loading-text="Sending OTP...">Send OTP</button>
        </form>
      </div>

      <div id="otpStep" class="portal-step">
        <button id="backToLookup" class="portal-back-btn" type="button">&larr; Back</button>
        <div class="portal-login-header">
          <span class="portal-login-mark"><i data-iconsax="shield-tick"></i></span>
          <div>
            <h2 class="portal-login-title">Verify your email</h2>
            <p id="otpHelpText" class="portal-login-copy">Enter the 6-digit code sent to your application email. It is valid for 30 minutes.</p>
          </div>
        </div>
        <form id="otpForm" class="portal-login-form">
          <input class="portal-input portal-otp-input" name="otp" maxlength="6" inputmode="numeric" autocomplete="one-time-code" placeholder="000000" required>
          <button id="verifyOtpBtn" class="portal-btn portal-btn-primary w-full" type="submit" data-idle-text="Verify and Continue" data-loading-text="Verifying...">Verify and Continue</button>
        </form>
        <button id="resendOtp" class="portal-btn portal-btn-ghost w-full portal-resend-btn" type="button" data-idle-text="Resend OTP" data-loading-text="Resending OTP...">Resend OTP</button>
      </div>
    </div>
  </div>
</section>
