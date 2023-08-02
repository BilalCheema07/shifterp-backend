<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $url;
    private $email;
    private $username;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url,$email,$username)
    {

        $this->url = $url;
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

        return $this->subject("Shift-ERP - Verification Email")
        ->from(config('mail.from.address'), 'Shift-ERP')
        ->view('emails.forgotPassword')
        ->with('url', $this->url)
        ->with('email', $this->email)
        ->with('name', $this->username);    }
}
