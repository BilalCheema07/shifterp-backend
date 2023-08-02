<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthResetMail extends Mailable
{
    use Queueable, SerializesModels;

    
    private $code;
    private $email;
    private $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code,$email,$username)
    {
        $this->code = $code;
        $this->email = $email;
        $this->username = $username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Shift-ERP - Authenticate Reset Email")
        ->from(config('mail.from.address'), 'Shift-ERP')
        ->view('emails.authResetMail')
        ->with('url', $this->code)
        ->with('email', $this->email)
        ->with('name', $this->username);
    }
}
