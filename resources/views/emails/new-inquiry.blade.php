<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Admission Inquiry</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #EEF2F7;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        table { border-collapse: collapse; }
        img { border: 0; outline: none; text-decoration: none; display: block; }

        .page { width: 100%; background: #EEF2F7; padding: 32px 14px; }
        .card { width: 100%; max-width: 580px; background: #ffffff; border-radius: 14px; overflow: hidden; }
        .header { background: #0B1D35; padding: 32px 40px 28px; }
        .logo-cell { width: 52px; height: 52px; background: #ffffff; border-radius: 12px; text-align: center; vertical-align: middle; }
        .logo { width: 42px; max-width: 42px; height: 42px; margin: 5px auto; object-fit: contain; }
        .school { color: #ffffff; font-size: 14px; font-weight: 700; line-height: 1.35; }
        .dept { color: rgba(255,255,255,0.56); font-size: 12px; line-height: 1.35; padding-top: 2px; }
        .title { color: #ffffff; font-size: 26px; line-height: 1.2; font-weight: 700; padding-top: 24px; margin: 0; }
        .subtitle { color: rgba(255,255,255,0.58); font-size: 14px; line-height: 1.55; padding-top: 8px; }
        .status { background: #C8A84B; color: #0B1D35; font-size: 10px; line-height: 1.4; letter-spacing: 1.6px; font-weight: 700; text-transform: uppercase; padding: 11px 40px; }
        .body { padding: 32px 40px 40px; }
        .intro { color: #4A5568; font-size: 14px; line-height: 1.7; margin: 0 0 24px; }
        .section-label { color: #A0AEC0; font-size: 9px; line-height: 1.3; letter-spacing: 2px; font-weight: 700; text-transform: uppercase; padding: 0 0 12px; }
        .details { width: 100%; }
        .details td { border-bottom: 1px solid #EDF2F7; padding: 12px 0; font-size: 13px; line-height: 1.45; vertical-align: top; }
        .details .label { width: 38%; color: #A0AEC0; }
        .details .value { color: #0B1D35; font-weight: 700; text-align: right; word-break: break-word; }
        .message-box { background: #FAFBFC; border-left: 3px solid #C8A84B; border-radius: 0 10px 10px 0; padding: 16px 18px; margin-top: 28px; }
        .message-title { color: #0B1D35; font-size: 12px; line-height: 1.4; letter-spacing: 1.2px; font-weight: 700; text-transform: uppercase; margin: 0 0 8px; }
        .message-text { color: #4A5568; font-size: 14px; line-height: 1.7; margin: 0; }
        .button-wrap { padding-top: 30px; text-align: center; }
        .button { background: #0B1D35; color: #ffffff !important; display: inline-block; border-radius: 10px; font-size: 14px; line-height: 1; font-weight: 700; text-decoration: none; padding: 14px 24px; }
        .footer { background: #0B1D35; color: rgba(255,255,255,0.34); font-size: 10px; line-height: 1.8; padding: 20px 40px; }
        .footer a { color: rgba(255,255,255,0.72); font-weight: 700; text-decoration: none; }

        @media (max-width: 500px) {
            .page { padding: 18px 10px !important; }
            .header, .body, .status, .footer { padding-left: 22px !important; padding-right: 22px !important; }
            .title { font-size: 23px !important; }
            .logo-cell { width: 48px !important; height: 48px !important; }
            .logo { width: 38px !important; max-width: 38px !important; height: 38px !important; }
            .details .label, .details .value { display: block; width: 100% !important; text-align: left !important; }
            .details .label { border-bottom: 0 !important; padding-bottom: 2px !important; }
            .details .value { padding-top: 0 !important; }
        }
    </style>
</head>
<body>
    <table role="presentation" class="page" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table role="presentation" class="card" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="logo-cell">
                                        <img class="logo" src="{{ $message->embed(public_path('assets/images/logo.jpg')) }}" width="42" height="42" alt="BTECH Logo">
                                    </td>
                                    <td style="padding-left: 12px;">
                                        <div class="school">Baliwag Polytechnic College</div>
                                        <div class="dept">Office of Admissions</div>
                                    </td>
                                </tr>
                            </table>
                            <h1 class="title">New website inquiry</h1>
                            <div class="subtitle">A sender submitted a message through the admission landing page.</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="status">New Inquiry Received</td>
                    </tr>

                    <tr>
                        <td class="body">
                            <p class="intro">Greetings! Below are the details provided by the sender. You can reply directly to the email address or review the message in the admin dashboard.</p>

                            <div class="section-label">Sender Details</div>
                            <table role="presentation" class="details" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="label">Full Name</td>
                                    <td class="value">{{ $inquiry->first_name }} {{ $inquiry->last_name }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Email Address</td>
                                    <td class="value"><a href="mailto:{{ $inquiry->email }}" style="color:#0B1D35; text-decoration:none;">{{ $inquiry->email }}</a></td>
                                </tr>
                                <tr>
                                    <td class="label">Subject</td>
                                    <td class="value">{{ $inquiry->subject }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Date/Time</td>
                                    <td class="value">{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>

                            <div class="message-box">
                                <p class="message-title">Message Content</p>
                                <p class="message-text">{{ $inquiry->message }}</p>
                            </div>

                            <div class="button-wrap">
                                <a class="button" href="{{ url('/admin/dashboard') }}">View in Admin Dashboard</a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="footer">
                            &copy; {{ date('Y') }} Baliwag Polytechnic College<br>
                            Baliwag, Bulacan &middot; Philippines<br>
                            <a href="mailto:{{ $inquiry->email }}">Reply to Sender</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
