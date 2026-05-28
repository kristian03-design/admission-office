<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Application Received - Baliwag Polytechnic College</title>
  <style>
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    img { border: 0; outline: none; text-decoration: none; display: block; }
    body { margin: 0; padding: 0; background-color: #EFF2F7; font-family: Arial, Helvetica, sans-serif; }
    @media only screen and (max-width: 600px) {
      .email-container { width: 100% !important; }
      .email-body-pad { padding: 24px 20px !important; }
      .header-pad { padding: 22px 20px !important; }
      .footer-pad { padding: 16px 20px !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#EFF2F7;">

{{-- Outer wrapper --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EFF2F7; padding:40px 16px;">
  <tr>
    <td align="center">

      {{-- Email card --}}
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

        {{-- ── STATUS BANNER: green confirmation strip ── --}}
        <tr>
          <td style="background-color:#EBF5EE; border-bottom:1px solid #C3DEC9; padding:13px 36px;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="22" height="22"
                  style="width:22px; height:22px; background-color:#2E7D4F; border-radius:50%; text-align:center; vertical-align:middle;">
                  {{-- Checkmark via HTML entity --}}
                  <span style="font-size:12px; color:#ffffff; font-weight:bold; line-height:22px; display:block;">&#10003;</span>
                </td>
                <td style="padding-left:10px; vertical-align:middle;">
                  <span style="font-size:13px; font-weight:bold; color:#1E5C36;">
                    Application successfully received
                  </span>
                  &nbsp;
                  <span style="font-size:11px; font-weight:bold; color:#2E7D4F; background-color:#D1EDDA;
                    border-radius:100px; padding:2px 10px; display:inline-block; letter-spacing:0.03em;">
                    Submitted
                  </span>
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
              Dear {{ $application->first_name ?? 'Applicant' }},
            </p>
            <p style="font-size:13px; color:#64748B; line-height:1.75; margin:0 0 28px;">
              Thank you for submitting your application to Baliwag Polytechnic College. Your application
              has been received and is now under review by our admissions team. Please retain your
              reference number for all future correspondence related to your application.
            </p>

            {{-- Reference number box --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
              <tr>
                <td style="background-color:#F1F5FB; border-left:3px solid #1B3468; border-radius:0 8px 8px 0; padding:14px 18px;">
                  <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.09em; color:#64748B; margin-bottom:5px;">
                    Your Reference Number
                  </div>
                  <div style="font-size:20px; font-weight:bold; color:#1B3468; letter-spacing:0.03em; font-family: 'Courier New', Courier, monospace;">
                    {{ $application->reference_number }}
                  </div>
                </td>
              </tr>
            </table>

            {{-- Details table --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
              style="border:1px solid #E2E8F0; border-radius:8px; overflow:hidden; margin-bottom:28px;">

              {{-- Row 1: Programme --}}
              <tr style="border-bottom:1px solid #E2E8F0;">
                <td width="36" style="width:36px; padding:14px 0 14px 16px; vertical-align:middle;">
                  <div style="width:32px; height:32px; background-color:#F1F5FB; border-radius:6px; text-align:center; line-height:32px;">
                    &#127979;
                  </div>
                </td>
                <td style="padding:14px 16px; border-bottom:1px solid #E2E8F0; vertical-align:middle;">
                  <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:#94A3B8; margin-bottom:3px;">
                    Programme Applied For
                  </div>
                  <div style="font-size:13px; font-weight:bold; color:#0F172A; line-height:1.4;">
                    {{ $application->first_choice ?? 'N/A' }}
                  </div>
                </td>
              </tr>

              {{-- Row 2: Date --}}
              <tr style="border-bottom:1px solid #E2E8F0;">
                <td width="36" style="width:36px; padding:14px 0 14px 16px; vertical-align:middle;">
                  <div style="width:32px; height:32px; background-color:#F1F5FB; border-radius:6px; text-align:center; line-height:32px;">
                    &#128197;
                  </div>
                </td>
                <td style="padding:14px 16px; border-bottom:1px solid #E2E8F0; vertical-align:middle;">
                  <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:#94A3B8; margin-bottom:3px;">
                    Date Submitted
                  </div>
                  <div style="font-size:13px; font-weight:bold; color:#0F172A;">
                    {{ optional($application->submitted_at ?? $application->created_at)->format('F d, Y \a\t h:i A') }}
                  </div>
                </td>
              </tr>

              {{-- Row 3: Status --}}
              <tr>
                <td width="36" style="width:36px; padding:14px 0 14px 16px; vertical-align:middle;">
                  <div style="width:32px; height:32px; background-color:#F1F5FB; border-radius:6px; text-align:center; line-height:32px;">
                    &#9997;
                  </div>
                </td>
                <td style="padding:14px 16px; vertical-align:middle;">
                  <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:#94A3B8; margin-bottom:3px;">
                    Application Status
                  </div>
                  <div style="font-size:13px; font-weight:bold; color:#0F172A;">
                    Under Review
                  </div>
                </td>
              </tr>

            </table>

            {{-- CTA Button --}}
            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%; margin-bottom:22px;">
              <tr>
                <td align="center" style="background-color:#1B3468; border-radius:8px;">
                  <a href="{{ route('application-status') }}"
                    style="display:block; background-color:#1B3468; color:#ffffff; font-size:13px;
                    font-weight:bold; text-decoration:none; padding:14px 28px; border-radius:8px;
                    letter-spacing:0.03em; text-align:center;">
                    Check Application Status &nbsp;&#8594;
                  </a>
                </td>
              </tr>
            </table>

            {{-- Portal note --}}
            <p style="font-size:12px; color:#94A3B8; line-height:1.75; margin:0; text-align:center;">
              Log in to the <strong style="color:#64748B;">Applicant Portal</strong> using your reference
              number and registered email to upload missing documents and track your status.
            </p>

            {{-- Divider --}}
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 0;">
              <tr>
                <td style="border-top:1px solid #E2E8F0; font-size:12px; color:#94A3B8; line-height:1.75; padding-top:20px; text-align:center;">
                  Questions? Contact the Admissions Office at
                  <a href="mailto:btechadmissionoffice@gmail.com" style="color:#1B3468; text-decoration:none;">btechadmissionoffice@gmail.com</a>
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
                <td colspan="2" style="padding-top:12px; font-size:10px; color:#CBD5E1; border-top:1px solid #E2E8F0; margin-top:12px;">
                  <div style="border-top:1px solid #E2E8F0; padding-top:12px; margin-top:0;">
                    This is an automated message. Please do not reply directly to this email.
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
      {{-- /Email card --}}

    </td>
  </tr>
</table>
</body>
</html>