<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use app\Mail\AuthResetMail;


class AuthResetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $email;
    private $username;
    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email =  new AuthResetMail( 
            $this->code,
            $this->email,
            $this->username
            );
        Mail::to($this->email)->send($email);
    }
}
