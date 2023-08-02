<?php

namespace App\Http\Controllers\Tenant\Profile;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;

class UserInfoController extends Controller
{
	public function userInfo()
	{
		$logged_in = auth()->user();

		tenancy()->initialize($logged_in->tenant_id);
		$user = User::where('id', $logged_in->tenant_user_id)->first();

		$data = ['user' => $user];
		
		return json_response(200, __('Profile.user_info'), $data);
	}
}
