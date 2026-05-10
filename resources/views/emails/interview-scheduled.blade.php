<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interview Scheduled — Baliwag Polytechnic College</title>
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
    .em-eyebrow {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #3B82F6;
      margin-bottom: 8px;
    }
    .em-hed { font-size: 26px; font-weight: 600; color: #0F172A; line-height: 1.2; margin-bottom: 8px; }
    .em-hed span { color: #2563EB; }
    .em-hed-desc { font-size: 13px; color: #64748B; line-height: 1.6; }

    /* ACTION STRIP */
    .action-strip {
      background: #EFF6FF;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 10px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .action-badge { display: flex; align-items: center; gap: 7px; }
    .action-dot { width: 7px; height: 7px; border-radius: 50%; background: #3B82F6; }
    .action-badge span { font-size: 11px; font-weight: 600; color: #2563EB; letter-spacing: 0.5px; }
    .action-step { font-size: 11px; color: #94A3B8; }

    /* SCHEDULE CARD */
    .sched-wrap {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-top: none;
      border-bottom: none;
      padding: 20px 36px;
    }
    .sched-card {
      background: #FFFFFF;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      display: flex;
      overflow: hidden;
    }
    .sched-date {
      flex: 1;
      padding: 20px 24px;
      border-right: 1px solid #F1F5F9;
    }
    .sched-time { flex: 1; padding: 20px 24px; }
    .sched-micro {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: #94A3B8;
      margin-bottom: 8px;
    }
    .sched-big { font-size: 22px; font-weight: 600; color: #0F172A; line-height: 1.15; }
    .sched-small { font-size: 11px; color: #94A3B8; margin-top: 6px; }
    .sched-time-num {
      font-family: 'Courier New', Courier, monospace;
      font-size: 30px;
      font-weight: 700;
      color: #2563EB;
      line-height: 1;
    }
    .sched-ampm { font-size: 12px; color: #64748B; margin-top: 4px; }
    .sched-note { font-size: 11px; color: #94A3B8; margin-top: 4px; }

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

    /* CALLOUT */
    .callout {
      border-radius: 10px;
      padding: 13px 15px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-top: 8px;
    }
    .callout-icon {
      width: 18px; height: 18px;
      border-radius: 50%;
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .callout p { font-size: 12px; line-height: 1.65; }
    .callout-warn { background: #FFFBEB; border: 1px solid #FDE68A; }
    .callout-warn .callout-icon { background: #FDE68A; color: #92400E; }
    .callout-warn p { color: #64748B; }
    .callout-warn strong { color: #D97706; }
    .callout-info { background: #EFF6FF; border: 1px solid #BFDBFE; }
    .callout-info .callout-icon { background: #BFDBFE; color: #1E40AF; }
    .callout-info p { color: #2563EB; }

    .divider { height: 1px; background: #F1F5F9; margin: 24px 0 0; }

    /* CLOSING */
    .closing { padding: 20px 0 0; }
    .closing p { font-size: 13px; color: #64748B; line-height: 1.7; }
    .sig { margin-top: 18px; display: flex; align-items: center; gap: 12px; }
    .sig-bar { width: 3px; height: 36px; background: #3B82F6; border-radius: 2px; flex-shrink: 0; }
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
      .em-head, .em-body, .action-strip, .sched-wrap { padding-left: 22px; padding-right: 22px; }
      .sched-card { flex-direction: column; }
      .sched-date { border-right: none; border-bottom: 1px solid #F1F5F9; }
      .em-footer { flex-direction: column; gap: 6px; padding: 18px 24px; text-align: center; }
      .footer-right { text-align: center; }
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
    <div class="em-eyebrow">Admissions Update</div>
    <div class="em-hed">You're in for <span>an interview.</span></div>
    <div class="em-hed-desc">Your application has progressed to the interview phase.</div>
  </div>

  <!-- ACTION STRIP -->
  <div class="action-strip">
    <div class="action-badge">
      <div class="action-dot"></div>
      <span>Action Required</span>
    </div>
    <span class="action-step">Step 2 of 3</span>
  </div>

  <!-- SCHEDULE CARD -->
  <div class="sched-wrap">
    <div class="sched-card">
      <div class="sched-date">
        <div class="sched-micro">Date</div>
        <div class="sched-big">{{ date('F d', strtotime($interview->interview_date)) }}<br>{{ date('Y', strtotime($interview->interview_date)) }}</div>
        <div class="sched-small">{{ date('l', strtotime($interview->interview_date)) }}</div>
      </div>
      <div class="sched-time">
        <div class="sched-micro">Time</div>
        <div class="sched-time-num">{{ date('h:i', strtotime($interview->interview_time)) }}</div>
        <div class="sched-ampm">{{ date('A', strtotime($interview->interview_time)) }}</div>
        <div class="sched-note">Arrive 15 min early</div>
      </div>
    </div>
  </div>

  <!-- BODY -->
  <div class="em-body">
    <div class="greeting">Hello, {{ $application->first_name ?? $interview->student_name }}!</div>
    <p>Congratulations on reaching the interview stage. Please review your schedule carefully and come prepared — bring any documents requested during your application and be ready to discuss your academic goals.</p>

    <div class="section-lbl">Application Details</div>
    <table class="info-table">
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

    <div class="section-lbl">Reminders</div>

    <div class="callout callout-warn">
      <div class="callout-icon">!</div>
      <p><strong>Dress appropriately.</strong> Business casual or formal attire is required. Avoid shorts, sleeveless tops, or slippers. Present yourself professionally.</p>
    </div>

    <div class="callout callout-info">
      <div class="callout-icon">i</div>
      <p>Need to reschedule? Contact the admissions office or visit BTECH Main Campus as soon as possible.</p>
    </div>

    <div class="divider"></div>

    <div class="closing">
      <p>We look forward to meeting you. Best of luck on your interview!</p>
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