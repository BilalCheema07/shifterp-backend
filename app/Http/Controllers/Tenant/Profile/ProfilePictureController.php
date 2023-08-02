<?php

namespace App\Http\Controllers\Tenant\Profile;

use App\Http\Controllers\Controller;
use App\Models\Tenant\{User, File};
use App\Http\Requests\Tenant\Profile\UploadProfilePictureRequest;
use App\Http\Resources\Tenant\Profile\ProfilePicCollection;

class ProfilePictureController extends Controller
{
	public function uploadProfilePicture(UploadProfilePictureRequest $request)
	{
		$file = new File;
		$user = User::find(auth()->user()->tenant_user_id);
		$old_dps = $user->profile_pic;
		$file->removeImage($user, $old_dps);

		if($request->has('image')){
			$type = 'profile_pic';
		$file = $file->createImage($request, $type);
		
		if ($file) {
			$user->files()->attach([$file->id]);
		}
	}
		$get_user = User::find($user->id);
		$profile_pic = $get_user->profile_pic;

		return json_response(200, __('Profile.updated'), new ProfilePicCollection($profile_pic));
	}
	
	public function getProfilePicture()
	{
		$user = User::find(auth()->user()->tenant_user_id);
		$profile_pic = $user->profile_pic;
		
		return json_response(200, __('Profile.get_pic'), new ProfilePicCollection($profile_pic));
	}
	
	public function removeProfilePicture()
	{
		$file = new File;
		$user = User::find(auth()->user()->tenant_user_id);
		$old_dps = $user->profile_pic;
		$file = $file->removeImage($user, $old_dps);
		
		return json_response(200, __('Profile.removed'));
	}
}
