<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Admission Inquiry — Baliwag Polytechnic College</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
      background: #F1F5F9;
      margin: 0;
      padding: 32px 14px;
      -webkit-font-smoothing: antialiased;
    }

    .wrapper { max-width: 560px; margin: 0 auto; width: 100%; }

    /* HEADER */
    .em-head {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-bottom: none;
      border-radius: 14px 14px 0 0;
      padding: 28px 36px 24px;
    }
    .em-logo-row {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 22px;
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
    .em-brand { font-size: 14px; font-weight: 600; color: #0F172A; }
    .em-brand-sub { font-size: 12px; color: #64748B; margin-top: 2px; }
    .em-hed { font-size: 26px; font-weight: 600; color: #0F172A; line-height: 1.2; margin-bottom: 8px; }
    .em-hed span { color: #D97706; }
    .em-hed-desc { font-size: 13px; color: #64748B; line-height: 1.6; }

    /* ALERT STRIP */
    .alert-strip {
      background: #FFFBEB;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 10px 36px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .alert-dot { width: 7px; height: 7px; border-radius: 50%; background: #F59E0B; }
    .alert-strip span { font-size: 11px; font-weight: 600; color: #D97706; letter-spacing: 0.5px; }

    /* BODY */
    .em-body {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 32px 36px 28px;
    }
    .em-body p { font-size: 13px; color: #64748B; line-height: 1.7; margin-bottom: 24px; }

    .section-lbl {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: #94A3B8;
      margin-bottom: 10px;
    }

    .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .info-table tr { border-bottom: 1px solid #F1F5F9; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: 11px 0; font-size: 13px; vertical-align: middle; }
    .info-table .lbl { color: #94A3B8; width: 40%; }
    .info-table .val { color: #0F172A; font-weight: 600; text-align: right; word-break: break-word; }
    .info-table .val a { color: #2563EB; text-decoration: none; }

    /* MESSAGE BOX */
    .msg-box {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 10px;
      padding: 18px 20px;
      margin-bottom: 24px;
    }
    .msg-title {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: #94A3B8;
      margin-bottom: 10px;
    }
    .msg-text { font-size: 13px; color: #475569; line-height: 1.7; }

    /* CTA BUTTON */
    .cta-wrap { text-align: center; padding-top: 4px; }
    .cta-btn {
      display: inline-block;
      background: #0F172A;
      color: #ffffff !important;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      padding: 13px 26px;
      letter-spacing: 0.2px;
    }

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
    .footer-right { font-size: 11px; }
    .footer-right a { color: #2563EB; text-decoration: none; font-weight: 500; }

    @media (max-width: 500px) {
      body { padding: 18px 10px; }
      .em-head, .em-body, .alert-strip { padding-left: 22px; padding-right: 22px; }
      .em-footer { flex-direction: column; gap: 6px; padding: 18px 24px; text-align: center; }
      .em-hed { font-size: 22px; }
      .info-table .lbl, .info-table .val { display: block; width: 100%; text-align: left; }
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <div class="em-head">
    <div class="em-logo-row">
      <div class="logo-box">
        <img src="{{ $message->embed(public_path('assets/images/logo.jpg')) }}" width="32" height="32" alt="BTECH Logo" />
      </div>
      <div>
        <div class="em-brand">Baliwag Polytechnic College</div>
        <div class="em-brand-sub">Office of Admissions</div>
      </div>
    </div>
    <div class="em-hed">New website <span>inquiry.</span></div>
    <div class="em-hed-desc">A sender submitted a message through the admissions landing page.</div>
  </div>

  <!-- ALERT STRIP -->
  <div class="alert-strip">
    <div class="alert-dot"></div>
    <span>New Inquiry Received</span>
  </div>

  <!-- BODY -->
  <div class="em-body">
    <p>Greetings! Below are the details provided by the sender. You can reply directly to the email address or review the message in the admin dashboard.</p>

    <div class="section-lbl">Sender Details</div>
    <table class="info-table">
      <tr>
        <td class="lbl">Full Name</td>
        <td class="val">{{ $inquiry->first_name }} {{ $inquiry->last_name }}</td>
      </tr>
      <tr>
        <td class="lbl">Email Address</td>
        <td class="val"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
      </tr>
      <tr>
        <td class="lbl">Subject</td>
        <td class="val">{{ $inquiry->subject }}</td>
      </tr>
      <tr>
        <td class="lbl">Date / Time</td>
        <td class="val">{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
      </tr>
    </table>

    <div class="section-lbl">Message Content</div>
    <div class="msg-box">
      <div class="msg-text">{{ $inquiry->message }}</div>
    </div>

    <div class="cta-wrap">
      <a class="cta-btn" href="{{ url('/admin/dashboard') }}">View in Admin Dashboard</a>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="em-footer">
    <div class="footer-left">
      &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
      Baliwag, Bulacan &middot; Philippines
    </div>
    <div class="footer-right">
      <a href="mailto:{{ $inquiry->email }}">Reply to Sender</a>
    </div>
  </div>

</div>
</body>
</html>