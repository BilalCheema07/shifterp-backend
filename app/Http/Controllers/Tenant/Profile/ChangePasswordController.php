<?php

namespace App\Http\Controllers\Tenant\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Profile\ChangePasswordRequest;

use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\User;

class ChangePasswordController extends Controller
{
	public function changePassword(ChangePasswordRequest $request)
	{
		$logged_in = auth()->user();
		$updated_pass = Hash::make($request->password);
		if (!Hash::check($request->current_password, $logged_in->password)) {
			return json_response(400, __('auth.incorrect_pass'));
		}

		$logged_in->password = $updated_pass;
		$logged_in->save();
		
		$user = User::find($logged_in->tenant_user_id);
		$user->password = $updated_pass;
		$user->save();

		return json_response(200, __('auth.password_update'));
	}
}
