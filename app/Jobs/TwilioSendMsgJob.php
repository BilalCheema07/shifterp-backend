<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Twilio\Rest\Client;
use Illuminate\Queue\SerializesModels;

class TwilioSendMsgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $numbr;
    private $msg;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($numbr,$msg)
    {
        $this->numbr = $numbr;
        $this->msg = $msg;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account_sid = config("app.twilio_sid");
        $auth_token = config("app.twilio_token");
        $twilio_number = config("app.twilio_from");
		
        $client = new Client($account_sid,$auth_token);
        $client->messages->create($this->numbr,[
            'from' => $twilio_number,
            'body' => $this->msg
        ]);
    }
}
