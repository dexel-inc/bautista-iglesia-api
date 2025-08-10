<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class NewsLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly ?string $description,
        private readonly ?string $filePath = null,
        private readonly string $emailSubject = 'Newsletter'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
            with: [
                'description' => $this->description,
                'subject' => $this->emailSubject,
            ],
        );
    }

    public function attachments(): array
    {
        if (!$this->filePath) {
            return [];
        }

        return [
            Attachment::fromStorageDisk(config('filesystems.default'), $this->filePath)
                ->as('newsletter.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
