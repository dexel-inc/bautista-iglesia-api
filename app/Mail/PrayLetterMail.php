<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PrayLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly ?string $description,
        private readonly ?string $filePath,
        private readonly string $emailSubject = 'Carta de Oración'
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
            view: 'emails.pray-letter',
            with: [
                'description' => $this->description,
                'subject' => $this->emailSubject,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk(config('filesystems.default'), $this->filePath)
                ->as('pray-letter.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
