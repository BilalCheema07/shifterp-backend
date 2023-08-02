<?php

namespace App\Http\Controllers\TenantAuth;

use Carbon\Carbon;
use App\Models\User;
use App\Services\Tenant\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantAuth\VerifyEmailRequest;

class VerifyEmailController extends Controller
{
	private $service;
	public function __construct(AuthService $auth_service){
		$this->service = $auth_service;
	}

	public function sendVerificationEmail(VerifyEmailRequest $request)
	{
		$result = $this->service->verifyEmail($request);
		return json_response(200, __('auth.verify_by_sms'),$result);
	}
	
	public function verifyEmail(VerifyEmailRequest $request)
	{
		
		if(!User::findEmailToken($request->email,$request->token)){				

			return json_response(403, __('auth.invalid_token'));
		}
		User::where('email', $request->email)->update([
			'email_verified_at' => Carbon::now(),
			'verification_token' => ''
		]);
		
		return json_response(200, __('auth.email_verified'));
	}	
}
	