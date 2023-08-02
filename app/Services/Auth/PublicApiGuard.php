<?php

namespace App\Services\Auth;

use App\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class PublicApiGuard extends BaseGuard
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requiredScopes = [ ApiToken::SCOPE_PUBLIC ];
    }

    public function validate(array $credentials = [])
    {
        return parent::validate($credentials);
    }
}
