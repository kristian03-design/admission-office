<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Application Received — Baliwag Polytechnic College</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #EEF2F7;
      padding: 48px 16px;
      -webkit-font-smoothing: antialiased;
    }

    .wrapper { max-width: 580px; margin: 0 auto; }

    /* HEADER */
    .header {
      background: #0B1D35;
      border-radius: 20px 20px 0 0;
      padding: 40px 44px 36px;
      position: relative;
      overflow: hidden;
    }
    .header::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 220px; height: 220px;
      border-radius: 50%;
      background: rgba(200,168,75,0.07);
    }
    .header::after {
      content: '';
      position: absolute;
      bottom: -40px; left: -40px;
      width: 150px; height: 150px;
      border-radius: 50%;
      background: rgba(255,255,255,0.025);
    }

    .logo-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 30px;
      position: relative;
    }
    .logo-box {
      width: 44px; height: 44px;
      border-radius: 10px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .logo-box img { width: 26px; height: 26px; object-fit: contain; }
    .school { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.3; }
    .dept { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }

    .header-title {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 40px;
      color: #fff;
      line-height: 1.05;
      margin-bottom: 10px;
      position: relative;
    }
    .header-title em { font-style: italic; color: #E8C96A; }

    .header-sub {
      font-size: 13px;
      color: rgba(255,255,255,0.35);
      font-weight: 300;
      position: relative;
    }

    /* STATUS STRIP */
    .status-strip {
      background: #C8A84B;
      padding: 11px 44px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: #0B1D35; }
    .status-strip span {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.8px;
      text-transform: uppercase;
      color: #0B1D35;
    }

    /* BODY */
    .body { background: #fff; padding: 36px 44px 44px; }

    .greeting {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 24px;
      color: #0B1D35;
      margin-bottom: 14px;
    }
    .body p { font-size: 14px; color: #4A5568; line-height: 1.8; }

    /* REFERENCE BOX */
    .ref-section {
      margin: 32px 0;
      border: 1.5px dashed #CBD5E0;
      border-radius: 14px;
      padding: 28px;
      text-align: center;
      background: #FAFBFC;
    }
    .ref-eyebrow {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #A0AEC0;
      margin-bottom: 10px;
    }
    .ref-number {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 36px;
      color: #0B1D35;
      letter-spacing: 3px;
    }
    .ref-note { margin-top: 10px; font-size: 11px; color: #A0AEC0; }

    /* SECTION LABEL */
    .section-lbl {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #A0AEC0;
      margin: 28px 0 14px;
    }

    /* TABLE */
    .info-table { width: 100%; border-collapse: collapse; }
    .info-table tr { border-bottom: 1px solid #EDF2F7; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table td { padding: 12px 0; font-size: 13px; vertical-align: middle; }
    .info-table .lbl { color: #A0AEC0; width: 45%; }
    .info-table .val { color: #0B1D35; font-weight: 600; text-align: right; }

    .divider { border: none; border-top: 1px solid #EDF2F7; margin: 28px 0 0; }

    /* CLOSING */
    .closing { padding: 24px 0 0; }
    .closing p { font-size: 14px; color: #4A5568; line-height: 1.8; }
    .sig { margin-top: 22px; display: flex; align-items: center; gap: 12px; }
    .sig-bar { width: 3px; height: 38px; background: #C8A84B; border-radius: 2px; flex-shrink: 0; }
    .sig-name { font-weight: 600; font-size: 13px; color: #0B1D35; }
    .sig-role { font-size: 11px; color: #A0AEC0; margin-top: 2px; }

    /* FOOTER */
    .footer {
      background: #0B1D35;
      border-radius: 0 0 20px 20px;
      padding: 22px 44px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .footer-left { font-size: 10px; color: rgba(255,255,255,0.3); line-height: 1.8; }
    .footer-right { font-size: 10px; color: rgba(255,255,255,0.2); text-align: right; }

    @media (max-width: 500px) {
      .header, .body, .status-strip { padding-left: 24px; padding-right: 24px; }
      .footer { flex-direction: column; padding: 20px 24px; text-align: center; }
      .footer-right { text-align: center; }
      .header-title { font-size: 30px; }
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- HEADER -->
  <div class="header">
    <div class="logo-row">
      <div class="logo-box">
        <img src="{{ $logo }}" alt="BTECH Logo">
      </div>
      <div>
        <div class="school">Baliwag Polytechnic College</div>
        <div class="dept">Office of Admissions</div>
      </div>
    </div>
    <div class="header-title">Application<br><em>Received.</em></div>
    <div class="header-sub">We've got everything we need from you.</div>
  </div>

  <!-- STATUS STRIP -->
  <div class="status-strip">
    <div class="status-dot"></div>
    <span>Under Review</span>
  </div>

  <!-- BODY -->
  <div class="body">
    <div class="greeting">Hello, {{ $application->first_name }}!</div>
    <p>Thank you for choosing BTECH. Your online admission application has been successfully received and is now being reviewed by our admissions team. We'll be in touch with the next steps shortly.</p>

    <!-- REFERENCE NUMBER -->
    <div class="ref-section">
      <div class="ref-eyebrow">Reference Number</div>
      <div class="ref-number">{{ $application->reference_number }}</div>
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

    <hr class="divider">

    <!-- CLOSING -->
    <div class="closing">
      <p>If you have any questions or need to follow up, please don't hesitate to reach out to our admissions office and have your reference number ready.</p>
      <div class="sig">
        <div class="sig-bar"></div>
        <div>
          <div class="sig-name"> BTECH Admissions Office</div>
          <div class="sig-role"> Baliwag Polytechnic College</div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-left">© {{ date('Y') }} Baliwag Polytechnic College<br>Baliwag, Bulacan · Philippines</div>
    <div class="footer-right">Admissions Office</div>
  </div>

</div>
</body>
</html>