<?php

namespace App\Http\Controllers\Tenant\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Profile\GoogleAuthRequest;
use App\Services\Tenant\Auth\GoogleAuthService;

class GoogleAuthController extends Controller
{
	private $google_auth;
	public function __construct(GoogleAuthService $google_auth)
	{
		$this->google_auth = $google_auth;
	}
	
	public function googleQrCode()
	{
		return $this->google_auth->googleQrCode();
	}
	
	public function googleVerifyCode(GoogleAuthRequest $request)
	{	
		return $this->google_auth->googleVerifyCode($request);
	}
	
	public function googleAuthActivator(GoogleAuthRequest $request)
	{	
		return $this->google_auth->googleAuthActivator($request);
	}
	
	public function googleAuthReset(GoogleAuthRequest $request)
	{
		return $this->google_auth->googleAuthReset($request);
	}
}
