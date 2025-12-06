<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User object
     */
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the message build.
     */
    public function build()
    {
        return $this->subject('Welcome, ' . $this->user->first_name)
            ->view('emails.user.register');
            // ->attach(public_path('img/logo.png'));
    }
}
