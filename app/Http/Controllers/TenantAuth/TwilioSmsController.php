<?php

namespace App\Http\Controllers\TenantAuth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Tenant\Auth\TwilioService;
use App\Http\Requests\TenantAuth\TwilioVerifyRequest;

class TwilioSmsController extends Controller
{
	private $service;
	public function __construct(TwilioService $twilio_service)
	{
		$this->service = $twilio_service;
	}

	// Verify Code for Login
	public function verifyMsg(TwilioVerifyRequest $request)
	{
		$auth_user = User::where("uuid", $request->id)->first();

		return $this->service->verifyCode($auth_user, $request);
	}

	// Resend Code for Login
	public function resendMsg(TwilioVerifyRequest $request)
	{
		$auth_user = User::where("uuid", $request->id)->first();

		return $this->service->getSmsCode($auth_user, "resend",0 , $request->token);
	}

	public function getCode(TwilioVerifyRequest $request)
	{
		$auth_user = auth()->user();
		return $this->service->getSmsCode($auth_user, "send", $request->phone_number ?? 0);
	}

	public function verifyUpdateNumber(TwilioVerifyRequest $request)
	{
		$auth_user = auth()->user();
		return $this->service->verifyNumber($auth_user, $request);
	}

	public function updateSms(TwilioVerifyRequest $request)
	{
		$auth_user = auth()->user();
		return $this->service->updateSmsField($auth_user, $request);
	}
}
