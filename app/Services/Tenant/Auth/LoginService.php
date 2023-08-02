<?php 
namespace App\Services\Tenant\Auth;

use App\Models\Tenant\User;
use App\Models\ProvisionAccount;
use App\Services\Tenant\Auth\TwilioService;
use App\Http\Resources\ProvisionAccountResource;

class LoginService 
{
	public function authEnableSms($user)
	{
		$rand_number = mt_rand(100000, 999999);
	    $msg =
	    	"Code has been sent for Login authentication. Your verification code is " . $rand_number . " ";
		$code = TwilioService::sendMsg($user, 0, $msg, $rand_number);

		$data = [
			"enable_sms"	=> $user->enable_sms,
			"code"			=> $code["verify_code"],
			"verify_token"	=> $code["verify_token"],
			"user_id"		=> $user->uuid
		];

		return json_response(200, __("auth.sms_send"), $data);
	}
	
	public function authDisableSms($user)
	{
		tenancy()->end();
		$tokenResult = $user->createToken($user->email)->plainTextToken;
		$user->save();

		$data = [
			"token"		=> $tokenResult,
			"code"		=> "2 factor Authentication is off",
			"user"		=> $user
		];
		
		if($user->provision_account_id > 0 ){
			$provision_account = ProvisionAccount::where("id",$user->provision_account_id)->first();
			$data["provision_account"] = new ProvisionAccountResource($provision_account);
		}

		if($user->role == "super-admin"){
			return json_response(200, __("auth.logged_in"), $data);
		}

		tenancy()->initialize($user->tenant_id);

		
		$my_user = User::find($user->tenant_user_id);
		
		$data["user_info"] = $my_user;
		$data["profile_pic"] = $my_user->profile_pic;
		
		return json_response(200, __("auth.logged_in"), $data);
	}
}