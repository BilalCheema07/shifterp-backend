<?php

namespace App\Http\Controllers\TenantAuth;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Auth\AuthService;
use App\Http\Requests\TenantAuth\ResetAuthVerifyRequest;

class AuthenticatorController extends Controller
{
	private $service;
	public function __construct(AuthService $auth_service)
	{
		$this->service = $auth_service;
	}

	public function resetAuth()
	{
		$login_user = auth()->user();
		$result = $this->service->resetAuth($login_user);
		return json_response(200, __('auth.verify_by_sms'), $result);
	}
	
	public function verifyResetAuth(ResetAuthVerifyRequest $request)
	{
		$auth_user = auth()->user();
		$result = $this->service->verifyResetAuth($auth_user,$request);
		return $result;
	}
	
	public function refresh()
	{
		$user = auth()->user();
		return json_response(200, __('auth.refresh'), $user);
	}
}
