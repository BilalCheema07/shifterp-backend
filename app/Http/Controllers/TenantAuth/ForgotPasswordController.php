<?php

namespace App\Http\Controllers\TenantAuth;

use App\Services\Tenant\Auth\AuthService;
use App\Http\Controllers\Controller;

use App\Http\Requests\TenantAuth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
	private $service;
	public function __construct(AuthService $auth_service)
	{
		$this->service = $auth_service;
	}

	public function sendEmailLink(ForgotPasswordRequest $request)
	{
		$result = $this->service->forgotPassword($request);
		return json_response(200, __('auth.email_send'), $result);
	}
	
	public function submitResetPasswordForm(ForgotPasswordRequest $request)
	{
		$result = $this->service->submitResetPass($request);
		return $result;
	}
}
	