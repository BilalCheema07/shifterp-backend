<?php

namespace App\Services\Auth;

use App\Models\Admin\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class AdminApiGuard extends BaseGuard
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->requiredScopes = [ ApiToken::SCOPE_ADMIN ];
    }

    public function validate(array $credentials = [])
    {
        $apiToken = $this->getToken();

        if ( ! $apiToken->isValid() || ! $apiToken->scopeIn($this->requiredScopes)) {
            return false;
        }

        return true;
    }

}
