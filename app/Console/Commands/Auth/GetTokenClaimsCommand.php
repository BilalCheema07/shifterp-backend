<?php
namespace App\Console\Commands\Auth;

use App\Console\Commands\BaseCommand;
use App\Services\Auth\ApiToken;


class GetTokenClaimsCommand extends BaseCommand
{
    public $signature = 'auth:getclaims {token}';
    public $description = "Gets the token claims for an existing token";

    public function handle()
    {
        $token = ApiToken::fromToken($this->argument('token'));

        $this->info(print_r($token->getClaims(), true));

    }
}
