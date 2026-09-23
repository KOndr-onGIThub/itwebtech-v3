<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        // OND-264: šablona je markdownová (`@component('mail::message')`).
        // S `view:` končila na produkci výjimkou „No hint path defined for
        // [mail]" — každá notifikace z formuláře selhala (mail_status=failed).
        return new Content(
            markdown: 'mail.contact-message',
        );
    }

    /**
     * OND-264: Přílohy z formuláře. Posílají se jen ty, co se vešly do
     * rozpočtu (`contact.uploads.mail_budget_mb`) — zbytek e-mail vypíše
     * i s cestou na disku, aby se nikdy neztratil potichu.
     *
     * @return list<Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->submission->attachments ?? [] as $file) {
            if (! ($file['mailed'] ?? false)) {
                continue;
            }

            $disk = $file['disk'] ?? config('contact.uploads.disk');

            if (! Storage::disk($disk)->exists($file['path'])) {
                continue;
            }

            $attachments[] = Attachment::fromStorageDisk($disk, $file['path'])
                ->as($file['name'])
                ->withMime($file['mime'] ?? 'application/octet-stream');
        }

        return $attachments;
    }
}
