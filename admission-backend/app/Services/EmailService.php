<?php
// ============================================================
// app/Services/EmailService.php
// SMTP email notifications via PHP's mail() or SMTP sockets
// Uses lightweight custom SMTP (no third-party library required)
// Install PHPMailer via: composer require phpmailer/phpmailer
// ============================================================

namespace App\Services;

use App\Config\Database;

class EmailService
{
    private array $config;
    private array $mailConfig;

    public function __construct()
    {
        $appConfig        = require __DIR__ . '/../../config/app.php';
        $this->config     = $appConfig;
        $this->mailConfig = $appConfig['mail'];
    }

    // ── Public notification methods ───────────────────────────

    public function sendWelcome(string $to, string $name): void
    {
        $subject = 'Welcome to ' . $this->config['name'];
        $body    = $this->renderTemplate('welcome', [
            'name'    => $name,
            'appName' => $this->config['name'],
        ]);
        $this->send($to, $name, $subject, $body);
    }

    public function sendApplicationSubmitted(
        string $to, string $name, string $appNo, string $programName
    ): void {
        $subject = "Application Received – {$appNo}";
        $body    = $this->renderTemplate('application_submitted', [
            'name'        => $name,
            'appNo'       => $appNo,
            'programName' => $programName,
            'appName'     => $this->config['name'],
        ]);
        $this->send($to, $name, $subject, $body);
    }

    public function sendStatusUpdated(
        string $to, string $name, string $appNo, string $programName, string $status
    ): void {
        $subject = "Application Update – {$appNo}";
        $body    = $this->renderTemplate('status_updated', [
            'name'        => $name,
            'appNo'       => $appNo,
            'programName' => $programName,
            'status'      => ucwords(str_replace('_', ' ', $status)),
            'appName'     => $this->config['name'],
        ]);
        $this->send($to, $name, $subject, $body);
    }

    public function sendPasswordReset(string $to, string $name, string $token): void
    {
        $resetUrl = $this->config['url'] . '/reset-password?token=' . $token;
        $subject  = 'Password Reset Request';
        $body     = $this->renderTemplate('password_reset', [
            'name'     => $name,
            'resetUrl' => $resetUrl,
            'appName'  => $this->config['name'],
        ]);
        $this->send($to, $name, $subject, $body);
    }

    // ── Core send method ──────────────────────────────────────

    /**
     * Send email using PHPMailer if available, otherwise native mail().
     * If you use Composer, add:  composer require phpmailer/phpmailer
     */
    private function send(string $to, string $toName, string $subject, string $body): void
    {
        $db = Database::pdo();

        try {
            if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
                $this->sendViaPhpMailer($to, $toName, $subject, $body);
            } else {
                $this->sendViaNativeMail($to, $toName, $subject, $body);
            }

            // Log success
            $stmt = $db->prepare(
                'INSERT INTO notifications (type, subject, body, sent_at)
                 VALUES (?, ?, ?, NOW())'
            );
            $stmt->execute(['email', $subject, $body]);

        } catch (\Throwable $e) {
            // Log failure (non-fatal)
            $stmt = $db->prepare(
                'INSERT INTO notifications (type, subject, body, failed_at, error)
                 VALUES (?, ?, ?, NOW(), ?)'
            );
            $stmt->execute(['email', $subject, $body, $e->getMessage()]);
            throw $e;  // Re-throw so callers can catch and swallow if needed
        }
    }

    private function sendViaPhpMailer(string $to, string $toName, string $subject, string $body): void
    {
        $m = new \PHPMailer\PHPMailer\PHPMailer(true);
        $c = $this->mailConfig;

        $m->isSMTP();
        $m->Host       = $c['host'];
        $m->SMTPAuth   = true;
        $m->Username   = $c['username'];
        $m->Password   = $c['password'];
        $m->SMTPSecure = $c['encryption'] === 'ssl'
            ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
            : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $m->Port       = $c['port'];

        $m->setFrom($c['from']['address'], $c['from']['name']);
        $m->addAddress($to, $toName);
        $m->isHTML(true);
        $m->Subject = $subject;
        $m->Body    = $body;
        $m->AltBody = strip_tags($body);

        $m->send();
    }

    private function sendViaNativeMail(string $to, string $toName, string $subject, string $body): void
    {
        $from    = $this->mailConfig['from'];
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            "From: {$from['name']} <{$from['address']}>",
            "Reply-To: {$from['address']}",
            'X-Mailer: PHP/' . PHP_VERSION,
        ]);

        $sent = mail($to, $subject, $body, $headers);
        if (!$sent) {
            throw new \RuntimeException("mail() failed for $to");
        }
    }

    // ── HTML Email Templates ──────────────────────────────────

    private function renderTemplate(string $template, array $vars): string
    {
        extract($vars);

        $baseStyle = '
            font-family: Arial, sans-serif; line-height: 1.6; color: #333;
            max-width: 600px; margin: 0 auto; padding: 20px;
        ';
        $headerStyle = '
            background: #1e40af; color: white; padding: 24px 20px;
            border-radius: 8px 8px 0 0;
        ';
        $bodyStyle   = 'background: #f9fafb; padding: 24px; border: 1px solid #e5e7eb;';
        $footerStyle = '
            background: #374151; color: #9ca3af; padding: 16px 20px; font-size: 12px;
            border-radius: 0 0 8px 8px; text-align: center;
        ';
        $btnStyle = '
            display: inline-block; background: #1e40af; color: white;
            padding: 12px 24px; border-radius: 6px; text-decoration: none;
            font-weight: bold; margin: 16px 0;
        ';

        $content = match ($template) {
            'welcome' => "
                <h2>Welcome, {$name}!</h2>
                <p>Your account on <strong>{$appName}</strong> has been created successfully.</p>
                <p>You can now log in and start your application.</p>
            ",
            'application_submitted' => "
                <h2>Application Received!</h2>
                <p>Dear {$name},</p>
                <p>We have received your application with the following details:</p>
                <table style='width:100%; border-collapse:collapse; margin:16px 0;'>
                    <tr><td style='padding:8px; border:1px solid #e5e7eb; font-weight:bold;'>Reference No.</td>
                        <td style='padding:8px; border:1px solid #e5e7eb;'>{$appNo}</td></tr>
                    <tr><td style='padding:8px; border:1px solid #e5e7eb; font-weight:bold;'>Program</td>
                        <td style='padding:8px; border:1px solid #e5e7eb;'>{$programName}</td></tr>
                    <tr><td style='padding:8px; border:1px solid #e5e7eb; font-weight:bold;'>Status</td>
                        <td style='padding:8px; border:1px solid #e5e7eb;'>Under Review</td></tr>
                </table>
                <p>We will notify you of any updates to your application.</p>
            ",
            'status_updated' => "
                <h2>Application Status Update</h2>
                <p>Dear {$name},</p>
                <p>Your application <strong>{$appNo}</strong> for <strong>{$programName}</strong>
                   has been updated.</p>
                <div style='background:#eff6ff; border-left:4px solid #1e40af; padding:16px; margin:16px 0;'>
                    <strong>New Status: {$status}</strong>
                </div>
                <p>Please log in to your account for more details.</p>
            ",
            'password_reset' => "
                <h2>Password Reset</h2>
                <p>Dear {$name},</p>
                <p>We received a request to reset your password. Click the button below:</p>
                <a href='{$resetUrl}' style='$btnStyle'>Reset Password</a>
                <p style='font-size:12px; color:#6b7280;'>
                    This link expires in 1 hour. If you did not request a reset, ignore this email.
                </p>
            ",
            default => "<p>Notification from {$appName}</p>",
        };

        return "
        <div style='$baseStyle'>
            <div style='$headerStyle'><h1 style='margin:0;'>{$appName}</h1></div>
            <div style='$bodyStyle'>$content</div>
            <div style='$footerStyle'>
                &copy; " . date('Y') . " {$appName}. This is an automated message, please do not reply.
            </div>
        </div>";
    }
}
