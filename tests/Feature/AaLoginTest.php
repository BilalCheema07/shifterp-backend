<?php

namespace Tests\Feature;

use Tests\TestCase;

class AaLoginTest extends TestCase
{
	public function testLoginApi()
	{
		$response = $this->postJson(
			'api/login',
			[
				'username' => $GLOBALS['tenant_username'],
				'password' => '12345678'
			]
		);
		global $token, $user_uuid, $user_email;

		$token = $response['data']['token'];
		
		$user_uuid = $response['data']['user_info']['uuid'];
		$user_email = $response['data']['user_info']['email'];

		$response->assertStatus(200);
	}

	/*public function testVerifySms()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson( 'api/verify_sms', 
		[
			'verify_code' => '673883',
		]);
		$response->assertStatus(200);
	}
	
	public function testResendSms()
	{
		$response = json_decode($this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('POST', '/api/resend_sms', 
		[
			'id' => '1',
		])->getContent());
		// $this->token = $response->data->token;
		// $usercrud =  new userCrudTest();
		// $usercrud->token = $this->token;
		$response->assertStatus(200);
	}
	
	public function testLogout()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->json('GET', '/api/logout');
		$response->assertStatus(200);
	}*/
	
	public function testRefresh()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', '/api/refresh');
		$response->assertStatus(200);
	}

	public function testForgetPassword()
	{
		$response = $this->postJson('/api/forget-password', 
			[
				'email' => $GLOBALS['user_email'],
				'url' => 'http:react.app/resetpass'
			]);

		global $forgot_email_code;
		$forgot_email_code = $response['data']['email_token'];

		$response->assertStatus(200);
	}
	
	public function testResetPassword()
	{
		$response = $this->postJson('/api/reset-password', [
			'email' => $GLOBALS['user_email'],
			'token' => $GLOBALS['forgot_email_code'],
			'password' => '87654321',
			'password_confirmation' => '87654321'
		]);

		$response->assertStatus(200);
	}

	public function testSendVerificationEmail()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postJson('/api/send-verification-mail', 
			[
				'url' => 'http:react.app/resetpass',
			]);

		global $verify_mail_code;
		$verify_mail_code = $response['data']['email_code'];

		$response->assertStatus(200);
	}

	public function testVerifyEmail()
	{
		$response =  $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postJson( '/api/verify-email', 
			[
				'email' => $GLOBALS['user_email'],
				'token' => $GLOBALS['verify_mail_code']
			]);

		$response->assertStatus(200);
	}

	/*public function testResetAuth()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->Json('GET', '/api/reset_auth');

		global $reset_auth_code;
		$reset_auth_code = $response['email_code'];

		$response->assertStatus(200);
	}

	public function testVerifyResetAuth()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postJson('/api/verify_reset_auth', 
			[
				'verify_code'   => $GLOBALS['email'],
				'password'      => 'ashir1231'
			]);

		$response->assertStatus(200);
	}*/
}
