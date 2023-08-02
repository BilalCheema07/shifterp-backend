<?php

namespace App\Http\Requests\TenantAuth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch (last(request()->segments())) {
			case "forget-password":
				return $this->sendEmail();
			default:
				return $this->resetForm();
		}
    }

    protected function sendEmail()
    {
        return [
            'email' => 'required|email|exists:users',
			'url' => 'required'
        ];
    }

    protected function resetForm()
    {
        return [
            'email' => 'required|email|exists:users',
			'token' => 'required',
			'password' => 'required|string|min:6|confirmed'
        ];
    }
}
