<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject("Shift-ERP - Verification Email")
        ->from(config('mail.from.address'), 'Shift-ERP')
        ->view('emails.forgotPassword')
        ->with('email', $this->email);
    }
}
