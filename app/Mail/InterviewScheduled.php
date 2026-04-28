<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;
    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct($interview, $application)
    {
        $this->interview = $interview;
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Schedule Confirmed - ' . ($this->application->reference_number ?? 'Admission'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.interview-scheduled',
            with: [
                'logo' => asset('assets/images/logo.jpg'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
