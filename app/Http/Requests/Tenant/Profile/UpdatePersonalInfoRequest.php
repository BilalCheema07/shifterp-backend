<?php

namespace App\Http\Requests\Tenant\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInfoRequest extends FormRequest
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
		return [
			'fname' => 'required',
			'lname' => 'required',
			'hire_date' => 'nullable',
			'release_date' => 'nullable',
			// 'phone' => 'required',
			'job_title' => 'required',
			'address' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zip' => 'required',
			'department' => 'required',
			'birth_date' => 'required',
			'shift' => 'required'
		];
	}
}
