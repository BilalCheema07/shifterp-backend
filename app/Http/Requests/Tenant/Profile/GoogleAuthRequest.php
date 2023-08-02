<?php

namespace App\Http\Requests\Tenant\Profile;

use Illuminate\Foundation\Http\FormRequest;

class GoogleAuthRequest extends FormRequest
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
			case "google-verify-code":
				return $this->verifyCode();
			case "google-auth-activator":
				return $this->authActivator();
			default:
				return $this->authReset();
		}
	}
	
	protected function verifyCode()
	{
		return [
			"secret" => 'required',
		];
	}
	
	protected function authActivator()
	{
		return [
			"verify_code" => 'required|integer',
		];
	}
	
	protected function authReset()
	{
		return [
			"backup_key" => 'required',
		];
	}
}
