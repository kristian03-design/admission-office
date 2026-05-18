<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public mixed $inquiry;
    public bool $isModel;
    public mixed $id;

    public function __construct(mixed $inquiry)
    {
        $this->inquiry = $inquiry;
        $this->isModel = ($inquiry instanceof ContactInquiry);
        $this->id = $this->isModel ? $inquiry->id : null;
    }

    public function envelope(): Envelope
    {
        $email = $this->inquiry->email ?? null;
        $senderName = trim(($this->inquiry->first_name ?? '') . ' ' . ($this->inquiry->last_name ?? ''));

        $replyTo = is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL)
            ? [new Address($email, $senderName)]
            : [];

        return new Envelope(
            from: new Address((string) config('mail.from.address', ''), (string) config('mail.from.name', '')),
            replyTo: $replyTo,
            subject: 'New Inquiry: ' . ($this->inquiry->subject ?? 'General Inquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-inquiry',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
