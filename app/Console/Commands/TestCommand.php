<?php
namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class TestCommand extends BaseCommand
{
    public $signature = 'test';
    public $description = "This is a test command";

    public function handle()
    {
        Storage::disk('s3')->get('s3://');
        Mail::send('emails.edi-notification', ['name' => 'Nate Divine', 'email' => 'nate@shifterp.com'], function($message) {
            $message->to('nate@shifterp.com', 'Nate Divine')
                ->from('no-reply@notifications.shifterp.com', 'EDI Notification: 940 Sent [BARESN]')
                ->attachFromStorage('/path/to/file', 'name.edi', ['mime' => 'text/plain'])
                ->subject('Welcome!');
        });
    }
}
