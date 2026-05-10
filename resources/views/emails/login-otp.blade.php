<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your OTP Code — BTECH Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #080F1A;
      margin: 0;
      padding: 32px 14px;
      -webkit-font-smoothing: antialiased;
    }

    .wrapper { max-width: 520px; margin: 0 auto; width: 100%; }

    /* HEADER */
    .header {
      background: #0B1D35;
      border-radius: 14px 14px 0 0;
      padding: 32px 40px 28px;
      position: relative;
      overflow: hidden;
      border-bottom: 1px solid rgba(200,168,75,0.15);
    }
    .header::before {
      content: '';
      position: absolute;
      top: -70px; right: -70px;
      width: 230px; height: 230px;
      border-radius: 50%;
      background: rgba(200,168,75,0.05);
    }
    .header::after {
      content: '';
      position: absolute;
      bottom: -40px; left: -40px;
      width: 140px; height: 140px;
      border-radius: 50%;
      background: rgba(255,255,255,0.02);
    }

    .logo-row {
      display: flex;
      align-items: center;
      gap: 12px;
      position: relative;
    }
    .logo-box {
      width: 52px; height: 52px;
      border-radius: 12px;
      background: #fff;
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .logo-box img { display: block; width: 42px; max-width: 42px; height: 42px; object-fit: contain; border: 0; outline: none; text-decoration: none; }
    .school { font-size: 14px; font-weight: 600; color: #fff; line-height: 1.35; }
    .dept { font-size: 12px; color: rgba(255,255,255,0.56); margin-top: 2px; line-height: 1.35; }

    /* SECURITY STRIP */
    .security-strip {
      background: #111D2E;
      padding: 10px 40px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .security-strip .mail-icon {
      width: 13px; height: 13px;
      color: #C8A84B;
      flex-shrink: 0;
      font-size: 13px;
      line-height: 13px;
    }
    .security-strip span {
      font-size: 10px;
      color: rgba(255,255,255,0.35);
      letter-spacing: 0.5px;
    }
    .security-strip strong { color: #C8A84B; font-weight: 600; }

    /* BODY */
    .body { background: #0E1F38; padding: 34px 40px; }

    .greeting {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 26px;
      color: #fff;
      margin-bottom: 10px;
      line-height: 1.25;
    }
    .greeting em { font-style: italic; color: #E8C96A; }

    .greeting-text {
      font-size: 13px;
      color: rgba(255,255,255,0.4);
      line-height: 1.65;
      margin-bottom: 28px;
    }

    /* OTP BLOCK */
    .otp-label {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.25);
      margin-bottom: 10px;
    }
    .otp-box {
      background: #091525;
      border: 1px solid rgba(200,168,75,0.22);
      border-radius: 14px;
      padding: 28px 22px;
      text-align: center;
      margin-bottom: 24px;
      position: relative;
      overflow: hidden;
    }
    .otp-box::before {
      content: '';
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 160px; height: 1px;
      background: linear-gradient(90deg, transparent, rgba(200,168,75,0.45), transparent);
    }
    .otp-digits {
      font-family: 'JetBrains Mono', 'Courier New', monospace;
      font-size: 42px;
      font-weight: 500;
      letter-spacing: 10px;
      color: #C8A84B;
      padding-left: 14px;
    }
    .otp-timer {
      margin-top: 14px;
      font-size: 11px;
      color: rgba(255,255,255,0.28);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .otp-timer .mail-icon {
      width: 12px; height: 12px;
      color: rgba(255,255,255,0.28);
      font-size: 12px;
      line-height: 12px;
    }
    .otp-timer strong { color: rgba(255,255,255,0.5); font-weight: 500; }

    /* WARNING */
    .warning-box {
      background: rgba(220,38,38,0.07);
      border: 1px solid rgba(220,38,38,0.18);
      border-left: 3px solid #DC2626;
      border-radius: 0 10px 10px 0;
      padding: 14px 16px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }
    .warning-box .mail-icon {
      width: 14px; height: 14px;
      color: #FCA5A5;
      flex-shrink: 0;
      margin-top: 1px;
      font-size: 14px;
      line-height: 14px;
    }
    .warning-box p { font-size: 12px; color: rgba(255,255,255,0.38); line-height: 1.65; }
    .warning-box strong { color: #FCA5A5; font-weight: 500; }

    /* FOOTER */
    .footer {
      background: #080F1A;
      border-radius: 0 0 14px 14px;
      padding: 20px 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid rgba(255,255,255,0.04);
    }
    .footer-left { font-size: 10px; color: rgba(255,255,255,0.2); line-height: 1.8; }
    .footer-right { font-size: 10px; color: rgba(255,255,255,0.15); text-align: right; }

    @media (max-width: 480px) {
      body { padding: 18px 10px; }
      .header, .body, .security-strip { padding-left: 22px; padding-right: 22px; }
      .footer { flex-direction: column; padding: 20px 24px; text-align: center; }
      .footer-right { text-align: center; }
      .logo-box { width: 48px; height: 48px; }
      .logo-box img { width: 38px; max-width: 38px; height: 38px; }
      .otp-digits { font-size: 32px; letter-spacing: 7px; padding-left: 7px; }
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <div class="header">
    <div class="logo-row">
      <div class="logo-box">
        <img src="{{ $message->embed(public_path('assets/images/logo.jpg')) }}" width="42" height="42" alt="BTECH Logo" />
      </div>
      <div>
        <div class="school">BTECH Admin Portal</div>
        <div class="dept">Baliwag Polytechnic College — Admissions System</div>
      </div>
    </div>
  </div>

  <!-- SECURITY STRIP -->
  <div class="security-strip">
    <span class="mail-icon">&#9670;</span>
    <span>Secure Authentication — <strong>One-Time Password</strong></span>
  </div>

  <!-- BODY -->
  <div class="body">
    <div class="greeting">Hello, <em>{{ $adminName }}.</em></div>
    <p class="greeting-text">
      A sign-in attempt was made on the BTECH Admin Dashboard. Use the code below to complete your authentication. Do not share this code with anyone.
    </p>

    <div class="otp-label">Your One-Time Password</div>

    <div class="otp-box">
      <div class="otp-digits">{{ $otp }}</div>
      <div class="otp-timer">
        <span class="mail-icon">&#9679;</span>
        Expires in <strong>10 minutes</strong>
      </div>
    </div>

    <div class="warning-box">
      <span class="mail-icon">!</span>
      <p><strong>Didn't request this?</strong> Ignore this email — your account remains secure. If this continues, consider changing your password immediately.</p>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-left">
      © {{ date('Y') }} Baliwag Polytechnic College<br>
      Baliwag, Bulacan · Philippines
    </div>
    <div class="footer-right">Automated message.<br>Do not reply.</div>
  </div>

</div>
</body>
</html>

