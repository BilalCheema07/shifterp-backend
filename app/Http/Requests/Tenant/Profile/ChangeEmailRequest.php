<?php

namespace App\Http\Requests\Tenant\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		switch (last(request()->segments())) {
			case "change-email":
				return $this->changeEmail();
			default:
				return $this->verifyChangeEmail();
			}
		}
		
		protected function changeEmail()
		{
			return [
				'email' => 'required|email|unique:users',
				'url'   => 'required'
			];
		}
		
		protected function verifyChangeEmail()
		{
			return [
				'email' => 'required|email',
				'token' => 'required'
			];
		}
	}
	