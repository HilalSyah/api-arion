<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password', // gunakan view, bukan markdown
            with: ['token' => $this->token], // kirim data ke view
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
