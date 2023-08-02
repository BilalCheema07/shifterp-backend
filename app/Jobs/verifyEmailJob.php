<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\{VerifiyEmail, ForgotEmail };

class VerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url;
    private $email;
    private $forgot;
    private $username;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url,$forgot,$email,$username)
    {
        $this->url = $url;
        $this->email = $email;
        $this->forgot = $forgot;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->forgot == 1){
            $email =  new ForgotEmail( 
                $this->url,
                $this->email,
                $this->username
                );
        } else{
            $email =  new VerifiyEmail( 
                $this->url,
                $this->email,
                $this->username
                );
        }

        Mail::to($this->email)->send($email);
    }
}
