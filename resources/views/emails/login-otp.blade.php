<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your OTP Code — BTECH Admin</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
      background: #F1F5F9;
      margin: 0;
      padding: 32px 14px;
      -webkit-font-smoothing: antialiased;
    }

    .wrapper { max-width: 520px; margin: 0 auto; width: 100%; }

    /* HEADER */
    .em-head {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-bottom: none;
      border-radius: 14px 14px 0 0;
      padding: 28px 36px 24px;
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .logo-box {
      width: 48px; height: 48px;
      border-radius: 10px;
      background: #EFF6FF;
      border: 1px solid #BFDBFE;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .logo-box img { display: block; width: 32px; height: 32px; object-fit: contain; }
    .em-brand { font-size: 14px; font-weight: 600; color: #0F172A; line-height: 1.35; }
    .em-brand-sub { font-size: 12px; color: #64748B; margin-top: 2px; }

    /* SECURITY STRIP */
    .security-strip {
      background: #EFF6FF;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 9px 36px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .security-strip .dot {
      width: 6px; height: 6px;
      border-radius: 50%;
      background: #3B82F6;
      flex-shrink: 0;
    }
    .security-strip span { font-size: 11px; font-weight: 500; color: #2563EB; letter-spacing: 0.3px; }

    /* BODY */
    .em-body {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 36px 36px 28px;
    }

    .greeting { font-size: 20px; font-weight: 600; color: #0F172A; margin-bottom: 10px; }
    .greeting span { color: #2563EB; }
    .desc { font-size: 13px; color: #64748B; line-height: 1.7; margin-bottom: 28px; }

    /* OTP BLOCK */
    .otp-label {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #94A3B8;
      margin-bottom: 10px;
    }
    .otp-card {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      padding: 28px 20px 22px;
      text-align: center;
      margin-bottom: 20px;
    }
    .otp-digits {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 16px;
    }
    .otp-digit {
      width: 52px; height: 64px;
      background: #FFFFFF;
      border: 1px solid #CBD5E1;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-family: 'Courier New', Courier, monospace;
      font-size: 28px;
      font-weight: 700;
      color: #2563EB;
    }
    .otp-sep { width: 10px; height: 2px; background: #CBD5E1; border-radius: 2px; }
    .otp-meta {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: 12px;
      color: #94A3B8;
    }
    .otp-meta .timer-dot { width: 6px; height: 6px; border-radius: 50%; background: #F59E0B; }
    .otp-meta strong { color: #D97706; font-weight: 500; }

    /* WARNING */
    .warn-box {
      background: #FFF5F5;
      border: 1px solid #FECACA;
      border-radius: 10px;
      padding: 14px 16px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }
    .warn-icon {
      width: 18px; height: 18px;
      border-radius: 50%;
      background: #FCA5A5;
      color: #991B1B;
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .warn-box p { font-size: 12px; color: #64748B; line-height: 1.65; }
    .warn-box strong { color: #DC2626; font-weight: 600; }

    /* FOOTER */
    .em-footer {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-radius: 0 0 14px 14px;
      padding: 18px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .footer-left { font-size: 11px; color: #94A3B8; line-height: 1.7; }
    .footer-right { font-size: 11px; color: #CBD5E1; text-align: right; }

    @media (max-width: 480px) {
      body { padding: 18px 10px; }
      .em-head, .em-body, .security-strip { padding-left: 22px; padding-right: 22px; }
      .em-footer { flex-direction: column; gap: 6px; padding: 18px 24px; text-align: center; }
      .footer-right { text-align: center; }
      .otp-digit { width: 44px; height: 56px; font-size: 24px; }
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <div class="em-head">
    <div class="logo-box">
      <img src="{{ $message->embed(public_path('assets/images/logo.jpg')) }}" width="32" height="32" alt="BTECH Logo" />
    </div>
    <div>
      <div class="em-brand">BTECH Admin Portal</div>
      <div class="em-brand-sub">Baliwag Polytechnic College — Admissions System</div>
    </div>
  </div>

  <!-- SECURITY STRIP -->
  <div class="security-strip">
    <div class="dot"></div>
    <span>Secure Authentication &nbsp;·&nbsp; One-Time Password</span>
  </div>

  <!-- BODY -->
  <div class="em-body">
    <div class="greeting">Hello, <span>{{ $adminName }}.</span></div>
    <p class="desc">A sign-in attempt was made on the BTECH Admin Dashboard. Use the code below to complete your authentication. Do not share this code with anyone.</p>

    <div class="otp-label">Your one-time password</div>

    <div class="otp-card">
      <div class="otp-digits">
        @php $digits = str_split($otp); @endphp
        @foreach($digits as $index => $digit)
          @if($index === 3)
            <div class="otp-sep"></div>
          @endif
          <div class="otp-digit">{{ $digit }}</div>
        @endforeach
      </div>
      <div class="otp-meta">
        <div class="timer-dot"></div>
        Expires in <strong>10 minutes</strong>
      </div>
    </div>

    <div class="warn-box">
      <div class="warn-icon">!</div>
      <p><strong>Didn't request this?</strong> Ignore this email — your account remains secure. If this continues, consider changing your password immediately.</p>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="em-footer">
    <div class="footer-left">
      &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
      Baliwag, Bulacan &middot; Philippines
    </div>
    <div class="footer-right">Automated message.<br>Do not reply.</div>
  </div>

</div>
</body>
</html>