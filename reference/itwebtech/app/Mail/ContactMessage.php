<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Adresa uživatele
     *
     * @var string|null
     */
    protected $email;

    /**
     * Obsah zprávy
     *
     * @var string|null
     */
    protected $message;

    /**
     * jmeno zadane do formulare
     *
     * @var string|null
     */
    protected $name;

    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $tel;

    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $web;
    
    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $app;
    
    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $eshop;
    
    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $other;

    /**
     * tel. cislo zadane do formulare
     *
     * @var string|null
     */
    protected $selected_price_option;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $email, ?string $message, string $name, ?string $tel, ?string $web, ?string $app, ?string $eshop, ?string $other, ?string $selected_price_option)
    {
        $this->email = $email;
        $this->message = $message;
        $this->name = $name;
        $this->tel = $tel;
        $this->web = $web;
        $this->app = $app;
        $this->eshop = $eshop;
        $this->other = $other;
        $this->selected_price_option = $selected_price_option;
    }
    
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Contact Message',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.contact',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    /**
     * Nakonfiguruj danou zprávu.
     *
     * @return $this
     */
    public function build(): ContactMessage
    {
        return $this->markdown('emails.contact', [
            'email' => $this->email,
            'name' => $this->name,
            'tel' => $this->tel,
            'message' => $this->message,
            'web' => $this->web,
            'app' => $this->app,
            'eshop' => $this->eshop,
            'other' => $this->other,
            'selected_price_option' => $this->selected_price_option,
        ])->subject('Email z kontaktního formuláře');
    }



}
