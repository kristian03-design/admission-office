<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTECH Admissions Reply</title>
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2f7;padding:32px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;background:#ffffff;border:1px solid #dbe3ef;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="padding:28px 34px;background:#0b1d35;color:#ffffff;">
                            <table role="presentation" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:48px;height:48px;background:transparent;border:0;text-align:center;vertical-align:middle;">
                                        <img src="{{ $message->embed(public_path('assets/images/logo_v2.png')) }}" width="42" height="42" alt="BTECH" style="display:block;margin:3px auto;border:0;border-radius:0;object-fit:contain;filter:brightness(1.25) contrast(1.15) saturate(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));">
                                    </td>
                                    <td style="padding-left:12px;">
                                        <div style="font-size:14px;font-weight:700;line-height:1.35;">Baliwag Polytechnic College</div>
                                        <div style="font-size:12px;color:rgba(255,255,255,.62);line-height:1.4;">Office of Admissions</div>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin:22px 0 0;font-size:25px;line-height:1.2;">Response to your inquiry</h1>
                            <p style="margin:8px 0 0;color:rgba(255,255,255,.7);font-size:14px;line-height:1.55;">Thank you for reaching out to BTECH Admissions.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 34px;">
                            <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#475569;">Hello {{ $inquiry->first_name ?: 'there' }},</p>
                            <div style="padding:18px 20px;background:#f8fafc;border-left:4px solid #c9933a;border-radius:0 12px 12px 0;color:#1f2a44;font-size:14px;line-height:1.75;white-space:pre-line;">{{ $replyMessage }}</div>
                            <p style="margin:22px 0 0;font-size:13px;line-height:1.7;color:#64748b;">Original inquiry: <strong style="color:#172033;">{{ $inquiry->subject }}</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 34px;background:#0b1d35;color:rgba(255,255,255,.5);font-size:11px;line-height:1.7;">
                            &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
                            Baliwag, Bulacan &middot; Philippines
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
