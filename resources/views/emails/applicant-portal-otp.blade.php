<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Applicant Portal Verification</title>
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
      <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="max-width:520px; width:100%; background-color:#ffffff; border-radius:14px; border:1px solid #E2E8F0;">
        <tr>
          <td style="background-color:#F8FAFC; padding:24px 36px; border-bottom:1px solid #E2E8F0; border-radius:14px 14px 0 0;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="48" height="48" style="width:48px; height:48px; text-align:center; vertical-align:middle;">
                  <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}" width="42" height="42" alt="BTECH" style="margin:3px auto; border:0; display:block; object-fit:contain;" />
                </td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:14px; font-weight:bold; color:#0F172A; line-height:1.35;">BTECH Applicant Portal</div>
                  <div style="font-size:12px; color:#64748B; padding-top:3px;">Baliwag Polytechnic College Admissions</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="background-color:#FFFBEB; padding:10px 36px; border-bottom:1px solid #FDE68A;">
            <span style="font-size:11px; font-weight:bold; color:#D97706;">Applicant Verification</span>
          </td>
        </tr>
        <tr>
          <td style="background-color:#ffffff; padding:36px 36px 28px;">
            <p style="font-size:20px; font-weight:bold; color:#0F172A; margin:0 0 10px;">Hello, {{ $application->first_name ?? 'Applicant' }}.</p>
            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:0 0 24px;">
              Use this one-time password to open your applicant portal for reference number <strong style="color:#0F172A;">{{ $application->reference_number }}</strong>.
            </p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; margin-bottom:20px;">
              <tr>
                <td align="center" style="padding:26px 20px 20px;">
                  <p style="font-family:'Courier New',Courier,monospace; font-size:34px; letter-spacing:8px; font-weight:bold; color:#D97706; margin:0 0 12px;">{{ $otp }}</p>
                  <p style="font-size:12px; color:#94A3B8; margin:0;">Expires in <strong style="color:#D97706;">10 minutes</strong></p>
                </td>
              </tr>
            </table>
            <p style="font-size:12px; color:#64748B; line-height:1.7; margin:0;">
              If you did not request this code, you can safely ignore this email.
            </p>
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
                <td style="font-size:11px; color:#CBD5E1; text-align:right;">Automated message.</td>
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
