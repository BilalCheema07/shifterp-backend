<?php
namespace App\Console\Commands\Auth;

use App\Console\Commands\BaseCommand;
use App\Services\Auth\ApiToken;

class GenerateTokenCommand extends BaseCommand
{
    public $signature = 'auth:token {userId} {scope} {expiry}';
    public $description = "Generates a user token";

    public function handle()
    {
        $scope = $this->argument('scope');
        $userId = $this->argument('userId');
        $expiry = $this->argument('expiry');

        $claims = [
            ApiToken::CLAIM_SCOPE => $scope,
            ApiToken::CLAIM_SUBJECT => $userId,
        ];

        $token = new ApiToken($claims);

        $token->setExpiry($expiry);

        $this->info($token->getToken());

    }
}
