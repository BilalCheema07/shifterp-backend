<?php 

namespace App\Services\Tenant\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Jobs\TwilioSendMsgJob;
use App\Models\Tenant\User as CUser;
use App\Services\Tenant\Auth\LoginService;

class TwilioService 
{
	public static function sendMsg($auth_user, $number = 0, $msg, $code, $sms_token = "")
	{
		$verify_token = $sms_token == "" ? md5($code) : $sms_token;
		if(isset($auth_user->sms_code) && $auth_user->sms_code > 0 ){
			$auth_user->sms_code = 0;
			$auth_user->code_expired_at = null;
			$auth_user->save();
		}
		$receiver_number = ($number != 0 ) ? $number : $auth_user->phone;        

		try{
			TwilioSendMsgJob::dispatch(
				$receiver_number,
				$msg,
			);
		} catch( Exception $e) {}
		
		$newDateTime = Carbon::now()->addSeconds(55);
		$carbon_array = (array) $newDateTime;
		$expired_at = $carbon_array["date"];
		
		$auth_user->sms_code = $code;
		$auth_user->sms_code_token = $verify_token;
		$auth_user->code_expired_at = $expired_at;
		if($number != 0){
			$auth_user->updated_number = $number; 
		}
		$auth_user->save();
		
		$data =  [
			"success"=> true,
			"verify_code" => $code,
			"verify_token" => $verify_token,
		];
		
		return $data;
	}
	
	public function verifyNumber($auth_user, $request)
	{
		if($auth_user->updated_number !=  $request->phone_number){
			return json_response(403, __("auth.invalid_number"));
		}

		if(Carbon::now()->lessThan($auth_user->code_expired_at) && $request->verify_code == $auth_user->sms_code) {
			$update = User::find($auth_user->id);
			$update->phone =  $request->phone_number;
			$update->sms_code = 0;
			$update->enable_sms = 1;
			$update->code_expired_at = null;
			$update->save();
			
			tenancy()->initialize($update->tenant_id);
			$user = CUser::where("id", $update->tenant_user_id)->first();
			$user->phone =  $request->phone_number;
			$user->save();
			tenancy()->end();
			
			$data = ["user" => $update];
			
			return json_response(200, __("auth.code_verified"), $data);
		}
		return json_response(403, __("auth.invalid_code"));
	}
	
	public function verifyCode($auth_user, $request)
	{
		if(Carbon::now()->lessThan($auth_user->code_expired_at) && $auth_user->sms_code == $request->verify_code){
			$auth_user->sms_code = 0;
			$auth_user->sms_code_token = null;
			$auth_user->code_expired_at = null;
			$auth_user->save();

			$resp = new LoginService;
			return $resp->authDisableSms($auth_user);
		}
		return json_response(403, __("auth.invalid_code"));
	}

	public function updateSmsField($auth_user, $request)
	{
		if(Carbon::now()->lessThan($auth_user->code_expired_at) && $request->verify_code == $auth_user->sms_code){
			$update = User::find($auth_user->id);
			$update->sms_code = 0;
			$update->enable_sms = ($request->func_name == "enable") ? 1 : 0;
			$update->sms_code_token = null;
			$update->code_expired_at = null;
			$update->save();

			$data = ["user" => $update];

			return json_response(
				200,
				$request->func_name == "enable" ? __("auth.verify_enable") : __("auth.verify_disabled"),
				$data
			);
		}
		return json_response(403, __("auth.invalid_code"));
	}

	public function getSmsCode($auth_user, $type, $number = 0, $sms_token = "")
	{
		$rand_number = mt_rand(100000, 999999);

		$msg = "Code has been " . $type . " to your phone number. Your verification code is " . $rand_number . " ";

		$code = $this->sendMsg($auth_user, $number, $msg, $rand_number, $sms_token);
		
		$data = [
			"enable_sms"	=> $auth_user->enable_sms,
			"code"			=> $code["verify_code"],
			"verify_token"	=> $code["verify_token"]
		];

		return json_response(
			200, 
			$type == "resend" ? __("auth.code_resend") : __("auth.code_send"), 
			$data
		);
	}
}