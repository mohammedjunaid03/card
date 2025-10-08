<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $userType;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $userType)
    {
        $this->token = $token;
        $this->userType = $userType;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'user_type' => $this->userType
        ]);

        return $this->subject('Reset Your Password - Health Card System')
                    ->view('emails.password-reset')
                    ->with([
                        'resetUrl' => $resetUrl,
                        'userType' => $this->userType,
                    ]);
    }
}
