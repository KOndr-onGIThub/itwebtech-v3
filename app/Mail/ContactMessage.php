<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * OND-173: Notifikace o novém leadu z kontaktního formuláře.
 *
 * Posílá se SYNCHRONNĚ (nikoli přes queue) — nezávisí tedy na běžícím
 * workeru. Reply-To je nastaven na e-mail odesílatele, aby Ondřej mohl
 * odpovědět přímo z klienta.
 */
class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ContactSubmission $submission,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->submission->subject
            ? 'Nová poptávka: '.$this->submission->subject
            : 'Nová zpráva z kontaktního formuláře';

        return new Envelope(
            subject: $subject,
            replyTo: [new Address($this->submission->email, $this->submission->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-message',
        );
    }
}
