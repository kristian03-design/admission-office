<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Application Received — Baliwag Polytechnic College</title>
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
    .em-hed span { color: #10B981; }
    .em-hed-desc { font-size: 13px; color: #64748B; line-height: 1.6; }

    /* STATUS STRIP */
    .status-strip {
      background: #ECFDF5;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 10px 36px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .status-dot { width: 7px; height: 7px; border-radius: 50%; background: #10B981; }
    .status-strip span { font-size: 11px; font-weight: 600; color: #059669; letter-spacing: 0.5px; }

    /* BODY */
    .em-body {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 32px 36px 28px;
    }

    .greeting { font-size: 20px; font-weight: 600; color: #0F172A; margin-bottom: 10px; }
    .em-body p { font-size: 13px; color: #64748B; line-height: 1.7; }

    /* REFERENCE BOX */
    .ref-box {
      background: #F8FAFC;
      border: 1.5px dashed #CBD5E1;
      border-radius: 12px;
      padding: 24px 20px;
      text-align: center;
      margin: 24px 0;
    }
    .ref-eyebrow {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #94A3B8;
      margin-bottom: 10px;
    }
    .ref-num {
      font-family: 'Courier New', Courier, monospace;
      font-size: 28px;
      font-weight: 700;
      color: #0F172A;
      letter-spacing: 2px;
      word-break: break-word;
    }
    .ref-note { font-size: 11px; color: #94A3B8; margin-top: 10px; }

    .section-lbl {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: #94A3B8;
      margin: 24px 0 10px;
    }

    .info-table { width: 100%; border-collapse: collapse; }
    .info-table tr { border-bottom: 1px solid #F1F5F9; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: 11px 0; font-size: 13px; vertical-align: middle; }
    .info-table .lbl { color: #94A3B8; width: 45%; }
    .info-table .val { color: #0F172A; font-weight: 600; text-align: right; }

    .divider { height: 1px; background: #F1F5F9; margin: 24px 0 0; }

    /* CLOSING */
    .closing { padding: 20px 0 0; }
    .closing p { font-size: 13px; color: #64748B; line-height: 1.7; }
    .sig { margin-top: 18px; display: flex; align-items: center; gap: 12px; }
    .sig-bar { width: 3px; height: 36px; background: #10B981; border-radius: 2px; flex-shrink: 0; }
    .sig-name { font-size: 13px; font-weight: 600; color: #0F172A; }
    .sig-role { font-size: 11px; color: #94A3B8; margin-top: 2px; }

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

    @media (max-width: 500px) {
      body { padding: 18px 10px; }
      .em-head, .em-body, .status-strip { padding-left: 22px; padding-right: 22px; }
      .em-footer { flex-direction: column; gap: 6px; padding: 18px 24px; text-align: center; }
      .footer-right { text-align: center; }
      .em-hed { font-size: 22px; }
      .ref-num { font-size: 22px; letter-spacing: 1px; }
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
    <div class="em-hed">Application <span>received.</span></div>
    <div class="em-hed-desc">We've got everything we need from you.</div>
  </div>

  <!-- STATUS STRIP -->
  <div class="status-strip">
    <div class="status-dot"></div>
    <span>Under Review</span>
  </div>

  <!-- BODY -->
  <div class="em-body">
    <div class="greeting">Hello, {{ $application->first_name }}!</div>
    <p>Thank you for choosing BTECH. Your online admission application has been successfully received and is now being reviewed by our admissions team. We'll be in touch with the next steps shortly.</p>

    <!-- REFERENCE NUMBER -->
    <div class="ref-box">
      <div class="ref-eyebrow">Reference Number</div>
      <div class="ref-num">{{ $application->reference_number }}</div>
      <div class="ref-note">Keep this safe — you'll need it throughout the process.</div>
    </div>

    <!-- APPLICATION DETAILS -->
    <div class="section-lbl">Application Details</div>
    <table class="info-table">
      <tr>
        <td class="lbl">Application Type</td>
        <td class="val">{{ $application->applicant_type }}</td>
      </tr>
      <tr>
        <td class="lbl">Course Choice</td>
        <td class="val">{{ $application->first_choice }}</td>
      </tr>
      <tr>
        <td class="lbl">Submitted On</td>
        <td class="val">{{ $application->created_at->format('M d, Y') }}</td>
      </tr>
    </table>

    <div class="divider"></div>

    <!-- CLOSING -->
    <div class="closing">
      <p>If you have any questions or need to follow up, please don't hesitate to reach out to our admissions office and have your reference number ready.</p>
      <div class="sig">
        <div class="sig-bar"></div>
        <div>
          <div class="sig-name">BTECH Admissions Office</div>
          <div class="sig-role">Baliwag Polytechnic College</div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="em-footer">
    <div class="footer-left">
      &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
      Baliwag, Bulacan &middot; Philippines
    </div>
    <div class="footer-right">Admissions Office</div>
  </div>

</div>
</body>
</html>