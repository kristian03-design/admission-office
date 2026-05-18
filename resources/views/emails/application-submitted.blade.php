<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Application Received - Baliwag Polytechnic College</title>
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
        <tr>
          <td style="background-color:#F8FAFC; padding:28px 36px 24px; border-bottom:1px solid #E2E8F0; border-radius:14px 14px 0 0;">
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
              <tr>
                <td width="48" height="48" style="width:48px; height:48px; text-align:center; vertical-align:middle;">
                  <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}" width="42" height="42" alt="BTECH" style="margin:3px auto; border:0; display:block; object-fit:contain;" />
                </td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:14px; font-weight:bold; color:#0F172A; line-height:1.35;">Baliwag Polytechnic College</div>
                  <div style="font-size:12px; color:#64748B; padding-top:3px;">Office of Admissions</div>
                </td>
              </tr>
            </table>
            <p style="font-size:26px; font-weight:bold; color:#0F172A; line-height:1.2; margin:0 0 8px;">Application <span style="color:#D97706;">received.</span></p>
            <p style="font-size:13px; color:#64748B; line-height:1.6; margin:0;">Your admission application has been submitted successfully.</p>
          </td>
        </tr>
        <tr>
          <td style="background-color:#FFFBEB; padding:10px 36px; border-bottom:1px solid #FDE68A;">
            <span style="font-size:11px; font-weight:bold; color:#D97706;">Reference Number: {{ $application->reference_number }}</span>
          </td>
        </tr>
        <tr>
          <td style="background-color:#ffffff; padding:32px 36px 28px;">
            <p style="font-size:20px; font-weight:bold; color:#0F172A; margin:0 0 10px;">Hello, {{ $application->first_name ?? 'Applicant' }}!</p>
            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:0 0 24px;">Please keep your reference number. You can use it with your submitted email address to open the Applicant Portal, track your status, update allowed details, and upload missing documents.</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; width:40%;">Reference No.</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0;">{{ $application->reference_number }}</td>
              </tr>
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0;">First Choice</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0;">{{ $application->first_choice ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:11px 0;">Date Submitted</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0;">{{ optional($application->submitted_at ?? $application->created_at)->format('M d, Y h:i A') }}</td>
              </tr>
            </table>
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
              <tr>
                <td align="center" style="background-color:#0F172A; border-radius:10px;">
                  <a href="{{ route('application-status') }}" style="display:inline-block; background-color:#0F172A; color:#ffffff; font-size:13px; font-weight:bold; text-decoration:none; padding:13px 26px; border-radius:10px; letter-spacing:0.2px;">Check Application Status</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="background-color:#F8FAFC; border-top:1px solid #E2E8F0; padding:18px 36px; border-radius:0 0 14px 14px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:11px; color:#94A3B8; line-height:1.7;">
                  &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
                  Baliwag, Bulacan &middot; Philippines
                </td>
                <td style="font-size:11px; color:#CBD5E1; text-align:right;">Admissions Office</td>
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
