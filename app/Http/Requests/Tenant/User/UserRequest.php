<?php

namespace App\Http\Requests\Tenant\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		switch (last(request()->segments())) {
			case "list":
				return $this->list();
			case "save":
				return $this->save();
			case "update-profile-pic":
				return $this->updateProfilePic();
			case "show":
				return $this->show();
			case "update":
				return $this->update();
			default:
				return $this->multiDelete();
		}
	}

	//Filters User Rules
	protected function list()
	{
		return [
			'role_ids' => 'nullable|array',
			'role_ids.*' => 'exists:roles,uuid',
			'status' => 'nullable|in:0,1',
			'search' => 'nullable|string',
			'order' => 'nullable|in:asc,desc'
		];
	}

	//Add User Rules
	protected function save()
	{
		return [
			'fname' => 'required', 
			'lname' => 'required', 
			'email' => 'required|email|unique:users,email', 
			'phone' => 'required', 
			'username' => 'required|unique:users,username', 
			'address' => 'required', 
			'city' => 'required', 
			'state' => 'required',
			'zip_code' => 'required', 
			'status' => 'required',
			'role_id' => 'required|exists:roles,uuid',
			'facilities.*' => 'nullable|exists:facilities,uuid',
			'permission_ids.*' => 'nullable|exists:permissions,uuid',
			'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
		];
	}

	//Updated User profile pic rules
	protected function updateProfilePic()
	{
		return [
			'user_id' => 'required|exists:users,uuid',
			'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
		];
	}

	//Get Single User information 
	protected function show()
	{
		return [
			'uuid' => 'required|exists:users,uuid'
		];
	}

	//Update User Rules
	protected function update()
	{
		return [
			'uuid' => 'required|exists:users,uuid',
			'fname' => 'required',
			'lname' => 'required',
			'email' => "required|email|unique:users,email,{$this->uuid},uuid",
			'phone' => 'required',
			'username' => "required|unique:users,username,{$this->uuid},uuid",
			'address' => 'required',
			'city' => 'required',
			'status' => 'required',
			'role_id' => 'nullable|exists:roles,uuid',
			'zip_code' => 'required',
			'state' => 'required',
			'facilities.*' => 'nullable|exists:facilities,uuid',
			'permission_ids.*' => 'nullable|exists:permissions,uuid',
			'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
		];
	}

	//Delete User Rules
	protected function multiDelete()
	{
		return [
			'ids' => 'required|array',
			'ids.*' => 'exists:users,uuid'
		];
	}
}
		