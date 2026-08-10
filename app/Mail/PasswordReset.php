<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    
    public function build()
    {
        return $this->subject('🔒 Reset Password Akun Anda')
            ->view('emails.auth.password-reset')
            ->with([
                'user' => $this->user,
                'token' => $this->token,
            ]);
    }
}
