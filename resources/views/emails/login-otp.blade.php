<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your OTP Code - BTECH Admin</title>
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

        <!-- HEADER -->
        <tr>
          <td style="background-color:#F8FAFC; padding:24px 36px; border-bottom:1px solid #E2E8F0; border-radius:14px 14px 0 0;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="48" height="48" style="width:48px; height:48px; background-color:transparent; border:0; text-align:center; vertical-align:middle;">
                  <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}" width="42" height="42" alt="BTECH" style="margin:3px auto; border:0; border-radius:0; display:block; object-fit:contain; filter:brightness(1.25) contrast(1.15) saturate(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));" />
                </td>
                <td style="padding-left:12px; vertical-align:middle;">
                  <div style="font-size:14px; font-weight:bold; color:#0F172A; line-height:1.35; font-family:Arial,Helvetica,sans-serif;">BTECH Admin Portal</div>
                  <div style="font-size:12px; color:#64748B; padding-top:3px; font-family:Arial,Helvetica,sans-serif;">Baliwag Polytechnic College &mdash; Admissions System</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- SECURITY STRIP -->
        <tr>
          <td style="background-color:#EFF6FF; padding:10px 36px; border-bottom:1px solid #BFDBFE;">
            <table role="presentation" cellpadding="0" cellspacing="0">
              <tr>
                <td width="8" style="vertical-align:middle;">
                  <div style="width:7px; height:7px; background-color:#3B82F6; border-radius:50%;"></div>
                </td>
                <td style="font-size:11px; font-weight:bold; color:#2563EB; padding-left:7px; font-family:Arial,Helvetica,sans-serif; vertical-align:middle;">
                  Secure Authentication &nbsp;&middot;&nbsp; One-Time Password
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- BODY -->
        <tr>
          <td style="background-color:#ffffff; padding:36px 36px 28px;">

            <p style="font-size:20px; font-weight:bold; color:#0F172A; margin:0 0 10px; font-family:Arial,Helvetica,sans-serif;">
              Hello, <span style="color:#2563EB;">{{ $adminName }}.</span>
            </p>
            <p style="font-size:13px; color:#64748B; line-height:1.7; margin:0 0 24px; font-family:Arial,Helvetica,sans-serif;">
              A sign-in attempt was made on the BTECH Admin Dashboard. Use the code below to complete your authentication. Do not share this code with anyone.
            </p>

            <p style="font-size:10px; font-weight:bold; letter-spacing:1.5px; text-transform:uppercase; color:#94A3B8; margin:0 0 10px; font-family:Arial,Helvetica,sans-serif;">
              Your One-Time Password
            </p>

            <!-- OTP CARD -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; margin-bottom:20px;">
              <tr>
                <td align="center" style="padding:26px 20px 20px;">

                  <!-- Digit row -->
                  @php $digits = str_split((string) $otp); @endphp
                  <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 14px;">
                    <tr>
                      @foreach($digits as $index => $digit)
                        @if($index === 3)
                          <td width="16" style="text-align:center; vertical-align:middle; padding:0 3px;">
                            <div style="width:10px; height:2px; background-color:#CBD5E1; border-radius:2px;"></div>
                          </td>
                        @endif
                        @if($index > 0 && $index !== 3)
                          <td width="6"></td>
                        @endif
                        <td width="52" height="64" style="width:52px; height:64px; background-color:#ffffff; border:1px solid #CBD5E1; border-radius:10px; text-align:center; vertical-align:middle;">
                          <span style="font-family:'Courier New',Courier,monospace; font-size:28px; font-weight:bold; color:#2563EB;">{{ $digit }}</span>
                        </td>
                      @endforeach
                    </tr>
                  </table>

                  <p style="font-size:12px; color:#94A3B8; margin:0; font-family:Arial,Helvetica,sans-serif;">
                    &bull;&nbsp; Expires in <strong style="color:#D97706; font-weight:bold;">10 minutes</strong>
                  </p>

                </td>
              </tr>
            </table>

            <!-- WARNING BOX -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFF5F5; border:1px solid #FECACA; border-radius:10px;">
              <tr>
                <td style="padding:14px 16px;">
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="20" height="20" style="width:20px; height:20px; background-color:#FCA5A5; border-radius:50%; text-align:center; vertical-align:top; font-size:11px; font-weight:bold; color:#991B1B; line-height:20px; font-family:Arial,Helvetica,sans-serif;">!</td>
                      <td style="font-size:12px; color:#64748B; line-height:1.65; padding-left:10px; vertical-align:top; font-family:Arial,Helvetica,sans-serif;">
                        <strong style="color:#DC2626;">Didn't request this?</strong> Ignore this email &mdash; your account remains secure. If this continues, consider changing your password immediately.
                      </td>
                    </tr>
                  </table>
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
                <td style="font-size:11px; color:#CBD5E1; text-align:right; font-family:Arial,Helvetica,sans-serif;">
                  Automated message.<br>Do not reply.
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
