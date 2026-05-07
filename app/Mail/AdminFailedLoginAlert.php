<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminFailedLoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $ip,
        public readonly ?string $attemptedEmail,
        public readonly string $userAgent,
        public readonly int $attempts,
        public readonly int $windowMinutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Security] Pokus o brute-force na /admin ('.$this->attempts.' pokusů)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin-failed-login-alert',
        );
    }
}
