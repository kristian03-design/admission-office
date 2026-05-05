<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Admission Inquiry</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f7f9; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(27, 53, 87, 0.1);">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f1e3d 0%, #1b3557 100%); padding: 40px 40px 30px 40px; text-align: center;">
                            <img src="{{ $message->embed(public_path('assets/images/logo.jpg')) }}" alt="BTECH Logo" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2); margin-bottom: 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">Baliwag Polytechnic College</h1>
                            <p style="color: #b8860b; margin: 5px 0 0 0; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">Admissions Office</p>
                        </td>
                    </tr>

                    <!-- Status Notification Bar -->
                    <tr>
                        <td style="padding: 12px 40px; background-color: #fdf6e3; border-bottom: 1px solid #f1e4c8; text-align: center;">
                            <span style="color: #856404; font-size: 12px; font-weight: 700; text-transform: uppercase;">🔔 New Website Inquiry Received</span>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 30px 0;">
                                Greetings! A new inquiry has been submitted through the official admission landing page. Below are the details provided by the sender:
                            </p>

                            <!-- Data Table -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td width="35%" style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #1b3557; font-size: 14px; font-weight: 700;">Full Name</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #333; font-size: 14px;">{{ $inquiry->first_name }} {{ $inquiry->last_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #1b3557; font-size: 14px; font-weight: 700;">Email Address</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #333; font-size: 14px;">
                                        <a href="mailto:{{ $inquiry->email }}" style="color: #1b3557; text-decoration: none; font-weight: 600;">{{ $inquiry->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #1b3557; font-size: 14px; font-weight: 700;">Subject</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #333; font-size: 14px; font-weight: 600;">{{ $inquiry->subject }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #1b3557; font-size: 14px; font-weight: 700;">Date/Time</td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #f0f2f5; color: #333; font-size: 14px;">{{ $inquiry->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>

                            <!-- Message Section -->
                            <div style="background-color: #f9fafb; border-radius: 12px; padding: 25px; border-left: 4px solid #b8860b;">
                                <h3 style="margin: 0 0 10px 0; color: #1b3557; font-size: 15px; font-weight: 700; text-transform: uppercase;">Message Content:</h3>
                                <p style="margin: 0; color: #4b5563; font-size: 15px; line-height: 1.7; font-style: italic;">
                                    "{{ $inquiry->message }}"
                                </p>
                            </div>

                            <!-- Action Button -->
                            <div style="margin-top: 40px; text-align: center;">
                                <a href="{{ url('/admin/dashboard') }}" style="background-color: #1b3557; color: #ffffff; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-size: 15px; font-weight: 700; display: inline-block; box-shadow: 0 4px 14px rgba(27, 53, 87, 0.3);">
                                    View in Admin Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px 40px; border-top: 1px solid #f0f2f5; text-align: center;">
                            <p style="margin: 0; color: #9aa5b1; font-size: 12px; line-height: 1.5;">
                                This is an automated notification from the <strong>BTECH Admission System</strong>.<br>
                                Please do not reply directly to this email.
                            </p>
                            <div style="margin-top: 15px;">
                                <a href="https://btech.edu.ph" style="color: #1b3557; font-size: 12px; font-weight: 700; text-decoration: none; margin: 0 10px;">Website</a>
                                <a href="mailto:{{ $inquiry->email }}" style="color: #1b3557; font-size: 12px; font-weight: 700; text-decoration: none; margin: 0 10px;">Reply to Sender</a>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <!-- System Info -->
                <p style="margin-top: 20px; color: #9aa5b1; font-size: 11px;">
                    &copy; {{ date('Y') }} Baliwag Polytechnic College. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
