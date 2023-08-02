<?php

namespace App\Http\Controllers\TenantAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantAuth\LoginRequest;
use App\Models\ProvisionAccount;
use App\Models\User;

use App\Services\Tenant\Auth\LoginService;

class LoginController extends Controller
{
	private $service;
	public function __construct(LoginService $login_service)
	{
		$this->service = $login_service;
	}

	public function login(LoginRequest $request)
	{
		$user = User::findUser($request->username);
		if (!$user || !Hash::check($request->password, $user->password) ) {
			return json_response(404, __("auth.failed"));
		}
		if($user->role !== "super-admin"){
			$provision_acc = ProvisionAccount::where("tenant_id", $user->tenant_id)
				->whereHas("subDetails", function ($query)  {
					$query->where("status", "active");
				})
				->first();

			if(!$provision_acc){
				return json_response(405, __("auth.company_login_error"));
			}
		}

		return ($user->enable_sms == 1) ? $this->service->authEnableSms($user) : $this->service->authDisableSms($user);
	}
	
	public function logout(Request $request)
	{
		$request->user()->currentAccessToken()->delete();
		session()->flush();		
		return json_response(200, __("auth.logged_out"));
	}
}