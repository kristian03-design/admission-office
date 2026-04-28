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

    public $inquiry;
    public $isModel;
    public $id;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
        $this->isModel = ($inquiry instanceof ContactInquiry);
        $this->id = $this->isModel ? $inquiry->id : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
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
