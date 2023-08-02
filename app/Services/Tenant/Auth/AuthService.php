<?php 
namespace App\Services\Tenant\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService 
{
	public function resetAuth($user)
	{
		$rand_number = mt_rand(100000,999999);
		
		$user->verification_token = $rand_number;
		$user->save();
		
		// AuthResetJob::dispatch(
		// 	$rand_number,
		// 	trim(strtolower($user->email)),
		// 	trim($user->username)
		// );
		
		$data = ['email_code' => $rand_number];
		return $data;
	}

	public function verifyResetAuth($auth_user,$request)
	{	
		if(!($auth_user->verification_token == $request->verify_code)) {
			return json_response(404, __('auth.invalid_code'));
		} elseif (!Hash::check($request->password, $auth_user->password)) {
			return json_response(401, __('auth.invalid_password'));
		}
		
		$auth_user = User::where('verification_token', $request->verify_code)->first();
		$auth_user->email_verified_at 	= Carbon::now();
		$auth_user->enable_sms    		= 0;
		$auth_user->verification_token	= '';
		$auth_user->save();
		
		return json_response(200, __('auth.email_verified'));
	}

	public function forgotPassword($request)
	{
		$token = Str::random(64);
		$user = User::where('email', $request->email)->first();
		DB::table('password_resets')->insert([
			'email' => $request->email, 
			'token' => $token, 
			'created_at' => Carbon::now()
		]);
		
		$url = $request->url . '?email=' . $request->email . '&token=' . $token;
		
		// try{
		// 	VerifyEmailJob::dispatch(
		// 		$url,
		// 		1,
		// 		trim(strtolower($user->email)),
		// 		trim($user->username)
		// 	);
		// }catch(Exception $e){
		// }
		
		$data = [
			'email_token'	=> $token,
			'url'	=> $url
		];
		return $data;
	}
	
	public function submitResetPass($request)
	{	
		$updatePassword = DB::table('password_resets')->where([

			'email' => $request->email, 
			'token' => $request->token
		])->first();
			
			if (!$updatePassword) {
				return json_response(403, __('auth.invalid_token'));
			}
			
			$user = User::where('email', $request->email)
			->update(['password' => Hash::make($request->password)]);
			
			DB::table('password_resets')->where(['email'=> $request->email])->delete();
			return json_response(200, __('auth.reset_pass'));
	}
	// public function verifyEmail($request){
	// 	DB::table('auth_users_password_resets')->where(['email'=> $request->email])->delete();
	// 	return json_response(200, __('auth.reset_pass'));
	// }

	public function verifyEmail($request)
	{
		$user = auth()->user();
		$token = Str::random(64);
		$url = $request->url . '?email=' . $user->email . '&token=' . $token;
		
		// VerifyEmailJob::dispatch(
		// 	$url,
		// 	0,
		// 	trim(strtolower($user->email)),
		// 	trim($user->username)
		// );
		
		$user->verification_token = $token;
		$user->save();
		
		$data = ['email_code'=> $token];
		return $data;
	}
}