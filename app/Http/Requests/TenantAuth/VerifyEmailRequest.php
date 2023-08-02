<?php

namespace App\Http\Requests\TenantAuth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		switch (last(request()->segments())) {
			case "send-verification-mail":
			return $this->sendVerificationMail();
			default:
			return $this->verifyEmail();
		}
	}
		
	protected function sendVerificationMail()
	{
		return [
			'url' => 'required'
		];
	}

 	protected function verifyEmail()
	{
		return [
			'email' => 'required|email|exists:users',
			'token' => 'required'
		];
	}
}
	