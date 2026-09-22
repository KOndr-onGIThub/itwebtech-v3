<?php

namespace App\Mail;

use App\Models\LandingLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * OND-264: Notifikace o poptávce z homepage, FAQ nebo landingu.
 *
 * Posílá se synchronně (bez queue), stejně jako [[ContactMessage]].
 * Reply-To míří na odesílatele, ať jde odpovědět rovnou z klienta.
 */
class LeadMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly LandingLead $lead,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nová poptávka z webu ('.($this->lead->source ?: 'web').')',
            replyTo: [new Address($this->lead->email, $this->lead->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lead-message',
        );
    }
}
