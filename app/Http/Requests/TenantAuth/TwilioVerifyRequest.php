<?php

namespace App\Http\Requests\TenantAuth;

use Illuminate\Foundation\Http\FormRequest;

class TwilioVerifyRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch (last(request()->segments())) {
			case "verify-updated-number":
				return $this->verifyCodeByNumber();
			case "send-sms":
				return $this->getCode();
			case "verify-sms":
				return $this->verifyCode();
			case "resend-sms":
				return $this->resendCode();
			default:
				return $this->updateSms();
		}
	}

	protected function verifyCodeByNumber() {
		return [
			"verify_code"	=> "required|integer",
			"phone_number"	=> "required"
		];
	}

	protected function verifyCode() {
		return [
			"id"			=> "required|exists:users,uuid",
			"token"			=> "required|exists:users,sms_code_token,uuid,{$this->id}",
			"verify_code"	=> "required|integer"
		];
	}

	protected function resendCode() {
		return [
			"id"			=> "required|exists:users,uuid",
			"token"			=> "required|exists:users,sms_code_token,uuid,{$this->id}"
		];
	}

	protected function getCode() {
		return [
			"phone_number"	=> "nullable"
		];
	}

	protected function updateSms() {
		return [
			"verify_code"	=> "required|integer",
			"func_name"		=> "required|in:enable,disable"
		];
	}
}
