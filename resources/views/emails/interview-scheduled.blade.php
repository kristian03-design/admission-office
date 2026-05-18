<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interview Scheduled — Baliwag Polytechnic College</title>
  <style>
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    img { border: 0; outline: none; text-decoration: none; display: block; }
    body { margin: 0; padding: 0; background-color: #F1F5F9; font-family: Arial, Helvetica, sans-serif; }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#F1F5F9;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F1F5F9; padding:32px 14px;">
  <tr>
    <td align="center">
      <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px; width:100%; background-color:#ffffff; border-radius:14px; border:1px solid #E2E8F0;">

        <!-- HEADER -->
        <tr>
          <td style="background-color:#F8FAFC; padding:28px 36px 24px; border-bottom:1px solid #E2E8F0; border-radius:14px 14px 0 0;">
            <!-- Logo row -->
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
              <tr>
                <td width="48" height="48" style="width:48px; height:48px; background-color:transparent; border:0; text-align:center; vertical-align:middle;">
                  <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}" width="42" height="42" alt="BTECH" style="margin:3px auto; border:0; border-radius:0; display:block; object-fit:contain; filter:brightness(1.25) contrast(1.15) saturate(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));" />
                </td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:14px; font-weight:bold; color:#0F172A; line-height:1.35; font-family:Arial,Helvetica,sans-serif;">Baliwag Polytechnic College</div>
                  <div style="font-size:12px; color:#64748B; padding-top:3px; font-family:Arial,Helvetica,sans-serif;">Office of Admissions</div>
                </td>
              </tr>
            </table>
            <p style="font-size:10px; font-weight:bold; letter-spacing:1.5px; text-transform:uppercase; color:#3B82F6; margin:0 0 8px; font-family:Arial,Helvetica,sans-serif;">Admissions Update</p>
            <p style="font-size:26px; font-weight:bold; color:#0F172A; line-height:1.2; margin:0 0 8px; font-family:Arial,Helvetica,sans-serif;">You're in for <span style="color:#2563EB;">an interview.</span></p>
            <p style="font-size:13px; color:#64748B; line-height:1.6; margin:0; font-family:Arial,Helvetica,sans-serif;">Your application has progressed to the interview phase.</p>
          </td>
        </tr>

        <!-- ACTION STRIP -->
        <tr>
          <td style="background-color:#EFF6FF; padding:10px 36px; border-bottom:1px solid #BFDBFE;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="vertical-align:middle;">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="8" style="vertical-align:middle;">
                        <div style="width:7px; height:7px; background-color:#3B82F6; border-radius:50%;"></div>
                      </td>
                      <td style="font-size:11px; font-weight:bold; color:#2563EB; padding-left:7px; font-family:Arial,Helvetica,sans-serif;">Action Required</td>
                    </tr>
                  </table>
                </td>
                <td style="font-size:11px; color:#94A3B8; text-align:right; font-family:Arial,Helvetica,sans-serif;">Step 2 of 3</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SCHEDULE CARD -->
        <tr>
          <td style="background-color:#F8FAFC; padding:20px 36px; border-bottom:1px solid #E2E8F0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border:1px solid #E2E8F0; border-radius:12px;">
              <tr>
                <!-- Date -->
                <td width="50%" style="padding:20px 24px; border-right:1px solid #F1F5F9; vertical-align:top;">
                  <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:0 0 8px; font-family:Arial,Helvetica,sans-serif;">Date</p>
                  <p style="font-size:22px; font-weight:bold; color:#0F172A; line-height:1.15; margin:0 0 6px; font-family:Arial,Helvetica,sans-serif;">
                    {{ date('F d', strtotime($interview->interview_date)) }}<br>
                    {{ date('Y', strtotime($interview->interview_date)) }}
                  </p>
                  <p style="font-size:11px; color:#94A3B8; margin:0; font-family:Arial,Helvetica,sans-serif;">{{ date('l', strtotime($interview->interview_date)) }}</p>
                </td>
                <!-- Time -->
                <td width="50%" style="padding:20px 24px; vertical-align:top;">
                  <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:0 0 8px; font-family:Arial,Helvetica,sans-serif;">Time</p>
                  <p style="font-size:30px; font-weight:bold; color:#2563EB; line-height:1; margin:0 0 4px; font-family:'Courier New',Courier,monospace;">{{ date('h:i', strtotime($interview->interview_time)) }}</p>
                  <p style="font-size:12px; color:#64748B; margin:0 0 4px; font-family:Arial,Helvetica,sans-serif;">{{ date('A', strtotime($interview->interview_time)) }}</p>
                  <p style="font-size:11px; color:#94A3B8; margin:0; font-family:Arial,Helvetica,sans-serif;">Arrive 15 min early</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- BODY -->
        <tr>
          <td style="background-color:#ffffff; padding:32px 36px 28px;">

            <p style="font-size:20px; font-weight:bold; color:#0F172A; margin:0 0 10px; font-family:Arial,Helvetica,sans-serif;">Hello, {{ $application->first_name ?? $interview->student_name }}!</p>
            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:0; font-family:Arial,Helvetica,sans-serif;">Congratulations on reaching the interview stage. Please review your schedule carefully and come prepared &mdash; bring any documents requested during your application and be ready to discuss your academic goals.</p>

            <!-- Application Details -->
            <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:24px 0 10px; font-family:Arial,Helvetica,sans-serif;">Application Details</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif; width:45%;">Reference No.</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">{{ $interview->reference_number ?? $application->reference_number ?? 'N/A' }}</td>
              </tr>
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">Course Choice</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">{{ $application->first_choice ?? $interview->program->name ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">Location</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">BTECH Main Campus</td>
              </tr>
            </table>

            <!-- Reminders label -->
            <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:24px 0 8px; font-family:Arial,Helvetica,sans-serif;">Reminders</p>

            <!-- Dress code callout -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFFBEB; border:1px solid #FDE68A; border-radius:10px; margin-bottom:8px;">
              <tr>
                <td style="padding:13px 15px;">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20" height="20" style="width:20px; height:20px; background-color:#FDE68A; border-radius:50%; text-align:center; vertical-align:top; font-size:11px; font-weight:bold; color:#92400E; line-height:20px; font-family:Arial,Helvetica,sans-serif;">!</td>
                      <td style="font-size:12px; color:#64748B; line-height:1.65; padding-left:10px; vertical-align:top; font-family:Arial,Helvetica,sans-serif;">
                        <strong style="color:#D97706;">Dress appropriately.</strong> Business casual or formal attire is required. Avoid shorts, sleeveless tops, or slippers. Present yourself professionally.
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Reschedule callout -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EFF6FF; border:1px solid #BFDBFE; border-radius:10px;">
              <tr>
                <td style="padding:13px 15px;">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20" height="20" style="width:20px; height:20px; background-color:#BFDBFE; border-radius:50%; text-align:center; vertical-align:top; font-size:11px; font-weight:bold; color:#1E40AF; line-height:20px; font-family:Arial,Helvetica,sans-serif;">i</td>
                      <td style="font-size:12px; color:#2563EB; line-height:1.65; padding-left:10px; vertical-align:top; font-family:Arial,Helvetica,sans-serif;">
                        Need to reschedule? Contact the admissions office or visit BTECH Main Campus as soon as possible.
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Divider -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 0;">
              <tr><td height="1" style="background-color:#F1F5F9; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>

            <!-- Closing -->
            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:20px 0 18px; font-family:Arial,Helvetica,sans-serif;">We look forward to meeting you. Best of luck on your interview!</p>

            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 22px;">
              <tr>
                <td align="center" style="background-color:#0F172A; border-radius:10px;">
                  <a href="{{ route('application-status') }}" style="display:inline-block; background-color:#0F172A; color:#ffffff; font-size:13px; font-weight:bold; text-decoration:none; padding:13px 22px; border-radius:10px; font-family:Arial,Helvetica,sans-serif; letter-spacing:0.2px;">Open Applicant Portal</a>
                </td>
              </tr>
            </table>

            <!-- Signature -->
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="3" style="background-color:#3B82F6; border-radius:2px; vertical-align:middle;">&nbsp;</td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:13px; font-weight:bold; color:#0F172A; font-family:Arial,Helvetica,sans-serif;">BTECH Admissions Office</div>
                  <div style="font-size:11px; color:#94A3B8; padding-top:3px; font-family:Arial,Helvetica,sans-serif;">Baliwag Polytechnic College</div>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- FOOTER -->
        <tr>
          <td style="background-color:#F8FAFC; border-top:1px solid #E2E8F0; padding:18px 36px; border-radius:0 0 14px 14px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:11px; color:#94A3B8; line-height:1.7; font-family:Arial,Helvetica,sans-serif;">
                  &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
                  Baliwag, Bulacan &middot; Philippines
                </td>
                <td style="font-size:11px; color:#CBD5E1; text-align:right; font-family:Arial,Helvetica,sans-serif;">Admissions Office</td>
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
