<?php

namespace App\Http\Controllers\Tenant\Profile;

use App\Models\Tenant\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Profile\UpdatePersonalInfoRequest;

class UpdatePersonalInfoController extends Controller
{
	public function updateUser(UpdatePersonalInfoRequest $request)
	{
		$auth_user = auth()->user();

		tenancy()->initialize($auth_user->tenant_id);

		User::where('id', $auth_user->tenant_user_id)
			->update([
				'fname' => $request->fname,
				'lname' => $request->lname,
				'job_title' => $request->job_title,
				'address' => $request->address,
				'city' => $request->city,
				'state' => $request->state,
				'zip_code' => $request->zip,
				'department' => $request->department,
				'shift' => $request->shift,
				'supervisor_name' => @$request->supervisor_name,
				'hire_date' => date('Y-m-d', strtotime($request->hire_date)),
				'release_date' => date('Y-m-d', strtotime($request->release_date)),
				'birth_date' => date('Y-m-d', strtotime($request->birth_date))
			]);
		
		$tenant_user = User::where('id', $auth_user->tenant_user_id)->first();
		
		$data = ['user'=> $tenant_user];
		
		return json_response(200, __('Profile.profile_update'), $data);
	}
}
