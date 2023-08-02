<?php

namespace App\Http\Controllers\Tenant\Profile;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Tenant\User as CUser;
use App\Http\Requests\Tenant\Profile\ChangeEmailRequest;

class ChangeEmailController extends Controller
{
	public function changeEmail(ChangeEmailRequest $request)
	{
		$token = Str::random(64);
		
		$login_user = auth()->user();
		$login_user->verification_token = $token;
		$login_user->updated_mail = $request->email;
		$login_user->save();
		
		// $this->email = $request->email;
		
		$url =  $request->url. '?email=' . $request->email . '&token=' . $token;
		
		// Mail::send('emails.emailVerification', ['url' => $url, 'verify_code' => 0], function($message) use($login_user){
			// 	$message->to($login_user->email);
			// 	$message->subject('Verify Email');
			// });
			
			$data = array(['email_code' => $token,'url'=>$url]);
			$msg = 'Verification email has been successfully sent to you.';
			
			return json_response(200, $msg, $data);
	}
		
	public function verifyChangedEmail(ChangeEmailRequest $request)
	{
		$logged_in = auth()->user();
		
		// dd($this->email);
		if($logged_in->updated_mail != $request->email) {
			$msg = 'Invalid email !';
			return json_response(403, $msg);
		}
		
		if($logged_in->verification_token != $request->token) {
			$msg = 'Invalid token!';
			return json_response(403, $msg);
		}
		tenancy()->end();
		
		$get_user = User::where('email',$request->email)->first();
		if($get_user){
			$msg = 'User Already Exists';
			return json_response(403, $msg);
		}
		
		tenancy()->initialize($logged_in->tenant_id);
		
		
		$logged_in->email_verified_at = Carbon::now();
		$logged_in->email = $request->email;
		$logged_in->verification_token = '';
		$logged_in->save();
		
		tenancy()->initialize($logged_in->tenant_id);
		
		$user = CUser::where('id', $logged_in->tenant_user_id)->first();
		// dd($user);
		$user->email = $request->email;
		$user->save();
		
		$msg = 'Email changed successfully.';
		
		return json_response(200, $msg);
	}
}
	