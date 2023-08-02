<?php

namespace App\Services\Auth;

use Carbon\Carbon;
use Firebase\JWT\JWT;

/**
 * Class ApiToken
 *
 * TODO: Needs unit test.
 *
 * @package App\Services\Auth
 */
class ApiToken
{
    const SCOPE_PUBLIC = 'public';
    const SCOPE_INTERNAL = 'internal';
    const SCOPE_ADMIN = 'admin';

    const INTERNAL_SCOPES = [
        self::SCOPE_ADMIN,
        self::SCOPE_INTERNAL
    ];

    private $secret = '';

    const CLAIM_SUBJECT = 'sub';
    const CLAIM_ISSUED_AT = 'iat';
    const CLAIM_EXPIRE = 'exp';
    const CLAIM_UNIQUE_ID = 'uid';
    const CLAIM_SCOPE = 'aud';

    private $claims = [];

    public function __construct($claims)
    {
        $this->setClaims($claims);
        $this->secret = env('JWT_SECRET');
    }

    public function setClaims($claims)
    {
        $this->claims = $claims;
    }

    public function getToken()
    {
        $this->setClaim(self::CLAIM_ISSUED_AT, Carbon::now()->timestamp);
        return JWT::encode($this->claims, $this->secret);
    }

    public static function fromToken($token)
    {
        $secret = env('JWT_SECRET');

        try {
            $claims = JWT::decode($token, $secret, ['HS256']);
        } catch (\Exception $e) {
            $claims = [];
        }

        $claims = (array)$claims;

        return new ApiToken($claims);
    }

    public function getClaimOptions()
    {
        return [
            self::CLAIM_SCOPE,
            self::CLAIM_EXPIRE,
            self::CLAIM_ISSUED_AT,
            self::CLAIM_UNIQUE_ID,
            self::CLAIM_SUBJECT,
        ];
    }

    public function setClaim($claim, $value)
    {
        if (in_array($claim, $this->getClaimOptions())) {
            $this->claims[$claim] = $value;
        }
    }

    public function getClaim($claim)
    {
        return $this->claims[$claim] ?? '';
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function getScope()
    {
        return $this->getClaim(self::CLAIM_SCOPE) ?? '';
    }

    public function scopeIn($scopes)
    {
        return in_array($this->getScope(), $scopes);
    }

    public function isInternalScope()
    {
        return $this->scopeIn([self::SCOPE_INTERNAL, self::SCOPE_ADMIN]);
    }

    public function scopeIs($scope)
    {
        return ($this->getScope() == $scope);
    }

    public function setExpiry($exp = null)
    {
        $expiry = Carbon::now()->timestamp;

        if (!empty($exp)) {
            $expiry = Carbon::parse($exp)->timestamp;
        }

        $this->setClaim(self::CLAIM_EXPIRE, $expiry);
    }

    public function setScope($scope)
    {
        $this->setClaim(self::CLAIM_SCOPE, $scope);
    }

    public function getExpiry()
    {
        $exp = $this->getClaim(self::CLAIM_EXPIRE);

        if ( ! empty($exp) && is_numeric($exp)) {
            return Carbon::parse($exp);
        }

        return false;
    }

    public function isValid()
    {
        if (empty($this->claims) || (is_array($this->claims) && empty($this->claims[self::CLAIM_SUBJECT]))) {
            return false;
        }

        return true;
    }
}
