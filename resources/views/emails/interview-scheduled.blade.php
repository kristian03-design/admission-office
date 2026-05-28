<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interview Scheduled — Baliwag Polytechnic College</title>
  <style>
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    img { border: 0; outline: none; text-decoration: none; display: block; }
    body { margin: 0; padding: 0; background-color: #EFF2F7; font-family: Arial, Helvetica, sans-serif; }
    @media only screen and (max-width: 600px) {
      .email-container { width: 100% !important; }
      .email-body-pad { padding: 24px 20px !important; }
      .header-pad { padding: 22px 20px !important; }
      .footer-pad { padding: 16px 20px !important; }
      .schedule-cell { display: block !important; width: 100% !important; border-right: none !important; border-bottom: 1px solid #E2E8F0 !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#EFF2F7;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EFF2F7; padding:40px 16px;">
  <tr>
    <td align="center">

      <table role="presentation" class="email-container" width="580" cellpadding="0" cellspacing="0"
        style="max-width:580px; width:100%; background-color:#ffffff; border-radius:12px; border:1px solid #D9E1EE; overflow:hidden;">

        {{-- ── HEADER: dark navy brand bar ── --}}
        <tr>
          <td class="header-pad" style="background-color:#1B3468; padding:26px 36px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="46" height="46"
                  style="width:46px; height:46px; background-color:rgba(255,255,255,0.12); border-radius:50%; text-align:center; vertical-align:middle;">
                  <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}"
                    width="28" height="28" alt="BPC"
                    style="display:block; margin:9px auto; object-fit:contain;" />
                </td>
                <td style="padding-left:14px; vertical-align:middle;">
                  <div style="font-size:15px; font-weight:bold; color:#FFFFFF; line-height:1.3; letter-spacing:0.01em;">
                    Baliwag Polytechnic College
                  </div>
                  <div style="font-size:11px; color:rgba(255,255,255,0.55); padding-top:3px; letter-spacing:0.02em;">
                    Office of Admissions &nbsp;&middot;&nbsp; Baliwag, Bulacan, Philippines
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── STATUS BANNER: blue interview strip ── --}}
        <tr>
          <td style="background-color:#EBF0FB; border-bottom:1px solid #BFCFEE; padding:13px 36px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="22" height="22"
                  style="width:22px; height:22px; background-color:#1B3468; border-radius:50%; text-align:center; vertical-align:middle;">
                  <span style="font-size:12px; color:#ffffff; font-weight:bold; line-height:22px; display:block;">&#9998;</span>
                </td>
                <td style="padding-left:10px; vertical-align:middle;">
                  <span style="font-size:13px; font-weight:bold; color:#1B3468;">
                    Interview scheduled &mdash; action required
                  </span>
                  &nbsp;
                  <span style="font-size:11px; font-weight:bold; color:#1B3468; background-color:#D0DBEF;
                    border-radius:100px; padding:2px 10px; display:inline-block; letter-spacing:0.03em;">
                    Step 2 of 3
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── SCHEDULE CARD ── --}}
        <tr>
          <td style="background-color:#F4F6FB; padding:20px 36px; border-bottom:1px solid #D9E1EE;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
              style="background-color:#ffffff; border:1px solid #D9E1EE; border-radius:10px; overflow:hidden;">
              <tr>
                {{-- Date --}}
                <td class="schedule-cell" width="50%"
                  style="padding:20px 24px; border-right:1px solid #E2E8F0; vertical-align:top;">
                  <p style="font-size:10px; font-weight:bold; letter-spacing:0.09em; text-transform:uppercase;
                    color:#94A3B8; margin:0 0 8px;">Date</p>
                  <p style="font-size:22px; font-weight:bold; color:#0F172A; line-height:1.15; margin:0 0 4px;">
                    {{ date('F d', strtotime($interview->interview_date)) }}<br>
                    {{ date('Y', strtotime($interview->interview_date)) }}
                  </p>
                  <p style="font-size:11px; color:#94A3B8; margin:0;">
                    {{ date('l', strtotime($interview->interview_date)) }}
                  </p>
                </td>
                {{-- Time --}}
                <td class="schedule-cell" width="50%" style="padding:20px 24px; vertical-align:top;">
                  <p style="font-size:10px; font-weight:bold; letter-spacing:0.09em; text-transform:uppercase;
                    color:#94A3B8; margin:0 0 8px;">Time</p>
                  <p style="font-size:28px; font-weight:bold; color:#1B3468; line-height:1; margin:0 0 4px;
                    font-family:'Courier New', Courier, monospace;">
                    {{ date('h:i', strtotime($interview->interview_time)) }}
                  </p>
                  <p style="font-size:12px; color:#64748B; margin:0 0 4px;">
                    {{ date('A', strtotime($interview->interview_time)) }}
                  </p>
                  <p style="font-size:11px; color:#94A3B8; margin:0;">Arrive 15 minutes early</p>
                </td>
              </tr>
              {{-- Location footer strip --}}
              <tr>
                <td colspan="2"
                  style="background-color:#F4F6FB; border-top:1px solid #E2E8F0; padding:11px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="font-size:11px; color:#64748B;">
                        &#128205; &nbsp;<strong style="color:#0F172A;">BTECH Main Campus</strong>
                        &nbsp;&mdash;&nbsp; Baliwag, Bulacan
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── BODY ── --}}
        <tr>
          <td class="email-body-pad" style="background-color:#ffffff; padding:34px 36px 30px;">

            {{-- Greeting --}}
            <p style="font-size:20px; font-weight:bold; color:#0F172A; margin:0 0 10px; line-height:1.3;">
              Dear {{ $application->first_name ?? $interview->student_name ?? 'Applicant' }},
            </p>
            <p style="font-size:13px; color:#64748B; line-height:1.75; margin:0 0 28px;">
              Congratulations on advancing to the interview stage of your application. Please review your
              schedule carefully and come prepared &mdash; bring any documents requested during your
              application and be ready to discuss your academic background and goals.
            </p>

            {{-- Application details --}}
            <div style="font-size:10px; font-weight:bold; letter-spacing:0.09em; text-transform:uppercase;
              color:#94A3B8; margin:0 0 10px;">Application Details</div>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
              style="border:1px solid #E2E8F0; border-radius:8px; overflow:hidden; margin-bottom:28px;">
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:12px 16px; border-bottom:1px solid #E2E8F0; width:45%;">
                  Reference No.
                </td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right;
                  padding:12px 16px; border-bottom:1px solid #E2E8F0; font-family:'Courier New',Courier,monospace;">
                  {{ $interview->reference_number ?? $application->reference_number ?? 'N/A' }}
                </td>
              </tr>
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:12px 16px; border-bottom:1px solid #E2E8F0;">
                  Course Choice
                </td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right;
                  padding:12px 16px; border-bottom:1px solid #E2E8F0;">
                  {{ $application->first_choice ?? $interview->program->name ?? 'N/A' }}
                </td>
              </tr>
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:12px 16px;">Interview Venue</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:12px 16px;">
                  BTECH Main Campus
                </td>
              </tr>
            </table>

            {{-- Reminders label --}}
            <div style="font-size:10px; font-weight:bold; letter-spacing:0.09em; text-transform:uppercase;
              color:#94A3B8; margin:0 0 10px;">Reminders</div>

            {{-- Dress code callout --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
              style="border:1px solid #FDE68A; border-left:3px solid #D97706; border-radius:0 8px 8px 0;
              background-color:#FFFBEB; margin-bottom:10px;">
              <tr>
                <td style="padding:13px 16px;">
                  <div style="font-size:11px; font-weight:bold; color:#B45309; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.06em;">
                    &#9888; &nbsp;Dress Code
                  </div>
                  <div style="font-size:12px; color:#64748B; line-height:1.65;">
                    Business casual or formal attire is required. Avoid shorts, sleeveless tops, or slippers.
                    Present yourself professionally at all times.
                  </div>
                </td>
              </tr>
            </table>

            {{-- Reschedule callout --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
              style="border:1px solid #BFDBFE; border-left:3px solid #1B3468; border-radius:0 8px 8px 0;
              background-color:#EBF0FB; margin-bottom:28px;">
              <tr>
                <td style="padding:13px 16px;">
                  <div style="font-size:11px; font-weight:bold; color:#1B3468; margin-bottom:4px; text-transform:uppercase; letter-spacing:0.06em;">
                    &#8505; &nbsp;Need to Reschedule?
                  </div>
                  <div style="font-size:12px; color:#64748B; line-height:1.65;">
                    Contact the Admissions Office or visit BTECH Main Campus as soon as possible
                    if you are unable to attend at the scheduled time.
                  </div>
                </td>
              </tr>
            </table>

            {{-- Closing --}}
            <p style="font-size:13px; color:#64748B; line-height:1.75; margin:0 0 24px;">
              We look forward to meeting you. Best of luck on your interview!
            </p>

            {{-- CTA Button --}}
            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:28px;">
              <tr>
                <td align="center" style="background-color:#1B3468; border-radius:8px;">
                  <a href="{{ route('application-status') }}"
                    style="display:block; background-color:#1B3468; color:#ffffff; font-size:13px;
                    font-weight:bold; text-decoration:none; padding:14px 28px; border-radius:8px;
                    letter-spacing:0.03em; text-align:center;">
                    Open Applicant Portal &nbsp;&#8594;
                  </a>
                </td>
              </tr>
            </table>

            {{-- Signature --}}
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="3" style="background-color:#1B3468; border-radius:2px; vertical-align:middle;">&nbsp;</td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:13px; font-weight:bold; color:#0F172A;">BTECH Admissions Office</div>
                  <div style="font-size:11px; color:#94A3B8; padding-top:3px;">Baliwag Polytechnic College</div>
                </td>
              </tr>
            </table>

            {{-- Divider --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 0;">
              <tr>
                <td style="border-top:1px solid #E2E8F0; padding-top:20px; font-size:12px;
                  color:#94A3B8; line-height:1.75; text-align:center;">
                  Questions? Contact us at
                  <a href="mailto:btechadmissionoffice@gmai.com" style="color:#1B3468; text-decoration:none;">btechadmissionoffice@gmai.com</a>
                  &nbsp;&middot;&nbsp;
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- ── FOOTER ── --}}
        <tr>
          <td class="footer-pad"
            style="background-color:#F8FAFC; border-top:1px solid #E2E8F0; padding:18px 36px; border-radius:0 0 12px 12px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:11px; color:#94A3B8; line-height:1.7;">
                  &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
                  Baliwag, Bulacan &middot; Philippines
                </td>
                <td style="text-align:right; vertical-align:top;">
                  <a href="#" style="font-size:11px; color:#94A3B8; text-decoration:none; display:block; margin-bottom:3px;">Privacy Policy</a>
                  <a href="#" style="font-size:11px; color:#94A3B8; text-decoration:none; display:block; margin-bottom:3px;">Admissions Office</a>
                  <a href="#" style="font-size:11px; color:#94A3B8; text-decoration:none; display:block;">Applicant Portal</a>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <div style="border-top:1px solid #E2E8F0; margin-top:12px; padding-top:12px;
                    font-size:10px; color:#CBD5E1;">
                    This is an automated message. Please do not reply directly to this email.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>

    </td>
  </tr>
</table>
</body>
</html>