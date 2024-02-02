<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.forgot_password',
            with: [
                'token' => $this->token,
                'resetUrl' => url(env('FRONTEND_URL', 'http://localhost:5173') . '/resetPassword?token=' . $this->token)
            ]
        );
    }
}
