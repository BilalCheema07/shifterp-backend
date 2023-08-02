<?php

namespace App\Services\Auth;

use App\Connection\SwitchFacility;
use App\Models\Admin\AccountFacility;
use App\Models\Admin\User;
use App\Models\Facility\User as UserFacility;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use \Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BaseGuard implements Guard
{
    use GuardHelpers;

    protected $apiToken;
    protected $request;
    protected $requiredScopes = [];
    protected $facility;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    protected function getToken()
    {
        if (empty($this->apiToken)) {
            $bearerToken    = $this->request->bearerToken();
            $this->apiToken = ApiToken::fromToken($bearerToken);;
        }

        return $this->apiToken;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        $apiToken = $this->getToken();

        if (!$apiToken->isValid()) {
            return false;
        }

        $userId = $apiToken->getClaim(ApiToken::CLAIM_SUBJECT);
        $user   = User::find($userId);

        if (empty($user)) {
            return false;
        }

        $this->setUser($user);

        return true;
    }

    public function user()
    {
        return $this->user;
    }
}
