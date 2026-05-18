<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Admission Inquiry — Baliwag Polytechnic College</title>
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
            <p style="font-size:26px; font-weight:bold; color:#0F172A; line-height:1.2; margin:0 0 8px; font-family:Arial,Helvetica,sans-serif;">New website <span style="color:#D97706;">inquiry.</span></p>
            <p style="font-size:13px; color:#64748B; line-height:1.6; margin:0; font-family:Arial,Helvetica,sans-serif;">A sender submitted a message through the admissions landing page.</p>
          </td>
        </tr>

        <!-- ALERT STRIP -->
        <tr>
          <td style="background-color:#FFFBEB; padding:10px 36px; border-bottom:1px solid #FDE68A;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="8" style="vertical-align:middle;">
                  <div style="width:7px; height:7px; background-color:#F59E0B; border-radius:50%;"></div>
                </td>
                <td style="font-size:11px; font-weight:bold; color:#D97706; padding-left:7px; font-family:Arial,Helvetica,sans-serif;">New Inquiry Received</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- BODY -->
        <tr>
          <td style="background-color:#ffffff; padding:32px 36px 28px;">

            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:0 0 24px; font-family:Arial,Helvetica,sans-serif;">Greetings! Below are the details provided by the sender. You can reply directly to the email address or review the message in the admin dashboard.</p>

            <!-- Section label -->
            <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:0 0 10px; font-family:Arial,Helvetica,sans-serif;">Sender Details</p>

            <!-- Details table -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; width:40%; font-family:Arial,Helvetica,sans-serif;">Full Name</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">{{ $inquiry->first_name }} {{ $inquiry->last_name }}</td>
              </tr>
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">Email Address</td>
                <td style="font-size:13px; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;"><a href="mailto:{{ $inquiry->email }}" style="color:#2563EB; text-decoration:none; font-weight:bold;">{{ $inquiry->email }}</a></td>
              </tr>
              <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">Subject</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">{{ $inquiry->subject }}</td>
              </tr>
              <tr>
                <td style="font-size:13px; color:#94A3B8; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">Date / Time</td>
                <td style="font-size:13px; color:#0F172A; font-weight:bold; text-align:right; padding:11px 0; font-family:Arial,Helvetica,sans-serif;">{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
              </tr>
            </table>

            <!-- Message box -->
            <p style="font-size:10px; font-weight:bold; letter-spacing:1.2px; text-transform:uppercase; color:#94A3B8; margin:0 0 10px; font-family:Arial,Helvetica,sans-serif;">Message Content</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; margin-bottom:24px;">
              <tr>
                <td style="padding:16px 18px; font-size:13px; color:#475569; line-height:1.7; font-family:Arial,Helvetica,sans-serif;">{{ $inquiry->message }}</td>
              </tr>
            </table>

            <!-- CTA Button -->
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
              <tr>
                <td align="center" style="background-color:#0F172A; border-radius:10px;">
                  <a href="{{ url('/admin/dashboard') }}" style="display:inline-block; background-color:#0F172A; color:#ffffff; font-size:13px; font-weight:bold; text-decoration:none; padding:13px 26px; border-radius:10px; font-family:Arial,Helvetica,sans-serif; letter-spacing:0.2px;">View in Admin Dashboard</a>
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
                <td style="font-size:11px; text-align:right; font-family:Arial,Helvetica,sans-serif;">
                  <a href="mailto:{{ $inquiry->email }}" style="color:#2563EB; text-decoration:none; font-weight:bold;">Reply to Sender</a>
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
