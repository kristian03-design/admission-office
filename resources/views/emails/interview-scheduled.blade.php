<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interview Scheduled — Baliwag Polytechnic College</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #E8EDF5;
      padding: 48px 16px;
      -webkit-font-smoothing: antialiased;
    }

    .em-wrap { max-width: 580px; margin: 0 auto; }

    /* HEADER */
    .em-head {
      background: #0B1D35;
      border-radius: 20px 20px 0 0;
      padding: 40px 44px 36px;
      position: relative;
      overflow: hidden;
    }
    .em-head::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 220px; height: 220px;
      border-radius: 50%;
      background: rgba(78,159,229,0.06);
    }
    .em-head::after {
      content: '';
      position: absolute;
      bottom: -40px; left: -40px;
      width: 150px; height: 150px;
      border-radius: 50%;
      background: rgba(255,255,255,0.025);
    }

    .em-logo-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 30px;
      position: relative;
    }
    .em-logo-box {
      width: 44px; height: 44px;
      border-radius: 10px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .em-logo-box img { width: 26px; height: 26px; object-fit: contain; }
    .em-school { font-size: 13px; font-weight: 600; color: #fff; line-height: 1.3; }
    .em-dept { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }

    .em-tag {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: #4E9FE5;
      margin-bottom: 10px;
      position: relative;
    }
    .em-title {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 40px;
      color: #fff;
      line-height: 1.05;
      margin-bottom: 10px;
      position: relative;
    }
    .em-title em { font-style: italic; color: #7DC4F5; }
    .em-subtitle {
      font-size: 13px;
      color: rgba(255,255,255,0.35);
      font-weight: 300;
      position: relative;
    }

    /* ACTION STRIP */
    .em-pill-row {
      background: #1A5FA8;
      padding: 11px 44px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .em-pill { display: inline-flex; align-items: center; gap: 7px; }
    .em-dot { width: 6px; height: 6px; border-radius: 50%; background: #7DC4F5; }
    .em-pill span {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.8);
    }
    .em-step { font-size: 10px; color: rgba(255,255,255,0.4); letter-spacing: 0.5px; }

    /* SCHEDULE CARD */
    .em-sched-bg { background: #1A3C66; padding: 24px 44px 0; }
    .em-sched {
      background: #0F2D54;
      border-radius: 14px;
      padding: 26px 30px;
      display: flex;
      gap: 0;
      border: 1px solid rgba(78,159,229,0.12);
    }
    .em-date-col {
      flex: 1;
      padding-right: 26px;
      border-right: 1px solid rgba(255,255,255,0.07);
    }
    .em-time-col { flex: 1; padding-left: 26px; }
    .em-col-label {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.3);
      margin-bottom: 8px;
    }
    .em-col-big {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 24px;
      color: #fff;
      line-height: 1.15;
    }
    .em-col-day { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 6px; }
    .em-time-num {
      font-size: 32px;
      font-weight: 600;
      color: #4E9FE5;
      letter-spacing: -0.5px;
      line-height: 1;
    }
    .em-time-ampm {
      font-family: 'Playfair Display', serif;
      font-size: 16px;
      color: rgba(255,255,255,0.6);
      margin-top: 2px;
    }
    .em-time-note { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 6px; }

    /* BRIDGE */
    .em-bridge { background: #1A3C66; padding: 22px 0 0; }
    .em-cap { background: #fff; border-radius: 14px 14px 0 0; height: 22px; }

    /* BODY */
    .em-body { background: #fff; padding: 0 44px 44px; }
    .em-greeting {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 24px;
      color: #0B1D35;
      padding-top: 34px;
      margin-bottom: 14px;
    }
    .em-body p { font-size: 14px; color: #4A5568; line-height: 1.8; }

    .em-section-lbl {
      font-size: 9px;
      font-weight: 600;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #A0AEC0;
      margin: 28px 0 14px;
    }

    /* DETAILS TABLE */
    .em-table { width: 100%; border-collapse: collapse; }
    .em-table tr { border-bottom: 1px solid #EDF2F7; }
    .em-table tr:last-child { border-bottom: none; }
    .em-table td { padding: 12px 0; font-size: 13px; vertical-align: middle; }
    .em-table .lbl { color: #A0AEC0; width: 45%; }
    .em-table .val { color: #0B1D35; font-weight: 600; text-align: right; }

    /* CALLOUT BOXES */
    .em-dress {
      background: #FFF7ED;
      border-left: 3px solid #F59E0B;
      border-radius: 0 10px 10px 0;
      padding: 14px 16px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin: 10px 0 0;
    }
    .em-dress p { font-size: 12px !important; color: #92400E !important; line-height: 1.65 !important; }
    .em-dress strong { color: #92400E; }

    .em-notice {
      background: #EFF6FF;
      border-left: 3px solid #3B82F6;
      border-radius: 0 10px 10px 0;
      padding: 14px 16px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin: 10px 0 0;
    }
    .em-notice p { font-size: 12px !important; color: #2563EB !important; line-height: 1.65 !important; }

    .em-icon { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

    .em-hr { border: none; border-top: 1px solid #EDF2F7; margin: 28px 0 0; }

    /* CLOSING */
    .em-closing { padding: 24px 0 0; }
    .em-closing p { font-size: 14px; color: #4A5568; line-height: 1.8; }
    .em-sig { margin-top: 22px; display: flex; align-items: center; gap: 12px; }
    .em-sig-bar { width: 3px; height: 38px; background: #3B82F6; border-radius: 2px; flex-shrink: 0; }
    .em-sig-name { font-weight: 600; font-size: 13px; color: #0B1D35; }
    .em-sig-role { font-size: 11px; color: #A0AEC0; margin-top: 2px; }

    /* FOOTER */
    .em-footer {
      background: #0B1D35;
      border-radius: 0 0 20px 20px;
      padding: 22px 44px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .em-footer-l { font-size: 10px; color: rgba(255,255,255,0.3); line-height: 1.8; }
    .em-footer-r { font-size: 10px; color: rgba(255,255,255,0.2); text-align: right; }

    @media (max-width: 500px) {
      .em-head, .em-body, .em-pill-row, .em-sched-bg { padding-left: 24px; padding-right: 24px; }
      .em-sched { flex-direction: column; gap: 20px; }
      .em-date-col { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.07); padding-right: 0; padding-bottom: 20px; }
      .em-time-col { padding-left: 0; }
      .em-footer { flex-direction: column; padding: 20px 24px; text-align: center; }
      .em-footer-r { text-align: center; }
      .em-title { font-size: 30px; }
    }
  </style>
</head>
<body>
<div class="em-wrap">

  <!-- HEADER -->
  <div class="em-head">
    <div class="em-logo-row">
      <div class="em-logo-box">
        <img src="{{ $logo }}" alt="BTECH Logo">
      </div>
      <div>
        <div class="em-school">Baliwag Polytechnic College</div>
        <div class="em-dept">Office of Admissions</div>
      </div>
    </div>
    <div class="em-tag">Admissions Update</div>
    <div class="em-title">You're in for<br><em>an Interview.</em></div>
    <div class="em-subtitle">Your application has progressed to the interview phase.</div>
  </div>

  <!-- ACTION STRIP -->
  <div class="em-pill-row">
    <div class="em-pill">
      <div class="em-dot"></div>
      <span>Action Required</span>
    </div>
    <span class="em-step">Step 2 of 3</span>
  </div>

  <!-- SCHEDULE CARD -->
  <div class="em-sched-bg">
    <div class="em-sched">
      <div class="em-date-col">
        <div class="em-col-label">Date</div>
        <div class="em-col-big">{{ date('F d', strtotime($interview->interview_date)) }}<br>{{ date('Y', strtotime($interview->interview_date)) }}</div>
        <div class="em-col-day">{{ date('l', strtotime($interview->interview_date)) }}</div>
      </div>
      <div class="em-time-col">
        <div class="em-col-label">Time</div>
        <div class="em-time-num">{{ date('h:i', strtotime($interview->interview_time)) }}</div>
        <div class="em-time-ampm">{{ date('A', strtotime($interview->interview_time)) }}</div>
        <div class="em-time-note">Arrive 15 min early</div>
      </div>
    </div>
    <div class="em-bridge"><div class="em-cap"></div></div>
  </div>

  <!-- BODY -->
  <div class="em-body">
    <div class="em-greeting">Hello, {{ $application->first_name ?? $interview->student_name }}!</div>
    <p>Congratulations on reaching the interview stage. Please review your schedule carefully and come prepared — bring any documents requested during your application and be ready to discuss your academic goals.</p>

    <div class="em-section-lbl">Application Details</div>
    <table class="em-table">
      <tr>
        <td class="lbl">Reference No.</td>
        <td class="val">{{ $interview->reference_number ?? $application->reference_number ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="lbl">Course Choice</td>
        <td class="val">{{ $application->first_choice ?? $interview->program->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <td class="lbl">Location</td>
        <td class="val">BTECH Main Campus</td>
      </tr>
    </table>

    <div class="em-section-lbl">Reminders</div>

    <!-- DRESS CODE NOTE -->
    <div class="em-dress">
      <svg class="em-icon" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="1.5">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
      </svg>
      <p><strong>Dress appropriately.</strong> Business casual or formal attire is required for the interview. Avoid casual clothing such as shorts, sleeveless tops, or slippers. Present yourself professionally.</p>
    </div>

    <!-- RESCHEDULE NOTICE -->
    <div class="em-notice">
      <svg class="em-icon" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="1.5">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <p>Need to reschedule? Contact the admissions office or visit the BTECH Admission Office at Main Campus as soon as possible.</p>
    </div>

    <hr class="em-hr">

    <!-- CLOSING -->
    <div class="em-closing">
      <p>We look forward to meeting you. Best of luck on your interview!</p>
      <div class="em-sig">
        <div class="em-sig-bar"></div>
        <div>
          <div class="em-sig-name">BTECH Admissions Office</div>
          <div class="em-sig-role">Baliwag Polytechnic College</div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="em-footer">
    <div class="em-footer-l">
      © {{ date('Y') }} Baliwag Polytechnic College<br>
      Baliwag, Bulacan · Philippines
    </div>
    <div class="em-footer-r">Admissions Office</div>
  </div>

</div>
</body>
</html>
