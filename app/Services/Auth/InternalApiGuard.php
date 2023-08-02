<?php

namespace App\Services\Auth;

use App\Models\Admin\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class InternalApiGuard extends BaseGuard
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requiredScopes = [ ApiToken::SCOPE_INTERNAL ];
    }

    public function validate(array $credentials = [])
    {
        return parent::validate($credentials);
    }

}
