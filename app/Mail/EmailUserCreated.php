<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailUserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $email;
    public $password;

    public function __construct($full_name, $email, $password)
    {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.create_user',
            with: [
                'email' => $this->email,
                'password' => $this->password,
                'full_name' => $this->full_name,
                'url_login' => url(env('FRONTEND_URL', 'http://localhost:5173') . '/')
            ]
        );
    }
}
