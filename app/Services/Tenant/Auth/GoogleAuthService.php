<?php 
namespace App\Services\Tenant\Auth;

use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class GoogleAuthService
{
	private $google2fa;
	public function __construct()
	{
		$this->google2fa = new Google2FA();
	}
	
	public function googleQrCode()
	{
		$g_secret = $this->google2fa->generateSecretKey(32);
		$this->google2fa->setEnforceGoogleAuthenticatorCompatibility(false);
		
		$user = auth()->user();
		
		$user->google2FA_secret = $g_secret;
		$user->save();
		$qrCodeUrl = $this->google2fa->getQRCodeUrl(
			$user->username,
			$user->email,
			$g_secret
		);
		
		$data = ['secret' => $g_secret, 'img' => $qrCodeUrl];
		
		return json_response(200, '',  $data);
	}
	
	public function googleVerifyCode($request)
	{
		$user = auth()->user();
		if($user->google2FA_secret == $request->secret) {
			$google_key = strtoupper(Str::random(32));
			
			$user->google2FA_key = $google_key;
			$user->save();
			
			$data = ['google_key' => $google_key];
			
			return json_response(200, __('auth.scanned'), $data);	
		}
		return json_response(400, __('auth.scanned_error'));
	}
	
	public function googleAuthActivator($request)
	{
		$auth_user = auth()->user();
		if(isset($auth_user->google2FA_secret) && $auth_user->google2FA_secret != null  ) {
			
			$valid = $this->google2fa->verifyKey($auth_user->google2FA_secret, $request->verify_code);
			if($valid)
			{
				if(isset($request->disable) && $request->disable == true){
					$auth_user->enable_google = 0;
					$auth_user->save();
					
					$data = ['enable_google' => $auth_user->enable_google];
					return json_response(200, __('auth.auth_disabled'), $data);
				}
				
				$auth_user->enable_google = 1;
				$auth_user->save();
				
				$data = ['enable_google' => $auth_user->enable_google];
				return json_response(200, __('auth.auth_enabled'), $data);
			}
			return json_response(400, __('auth.invalid_token'));
		}
		return json_response(400, __('auth.enable_auth'));
	}
	
	public function googleAuthReset($request)
	{
		$user = auth()->user();
		if ($user->google2FA_key == $request->backup_key) {
			$user->google2FA_key = null;
			$user->google2FA_secret = null;
			$user->enable_google = 0;
			$user->save();
			
			return json_response(200, __('auth.reset_auth'));
		}
		return json_response(400, __('auth.invalid_key')); 
	}
}