<?php

namespace App\Http\Controllers\Tenant\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\User\UserRequest;
use App\Http\Resources\Tenant\Profile\ProfilePicResource;
use App\Http\Resources\Tenant\User\{UserCollection, UserResource};
use App\Models\ProvisionAccount;
use App\Models\Tenant\User as CUser;
use App\Models\Tenant\File;
use App\Services\Tenant\User\UserService;

class UserCrudController extends Controller
{
	private $user_service; 
	public function __construct(UserService $user_service)
	{
		return $this->user_service = $user_service;
	}
	
	public function list(UserRequest $request)
	{
		$users = CUser::with('roles', 'facilities', 'facilities.primaryContact', 'profile_pic', 'permissions')
		->where('id', '<>', auth()->user()->tenant_user_id);
		
		$users = $request->role_ids ? $users->roleString($request->role_ids) : $users;
		$users = isset($request->status) ? $users->where('status', $request->status) : $users;
		$users = $users->orderBy('id', $request->order ?? 'asc');
		$users = $request->search ? $users->searchUser($request->search) : $users;
		
		$users = $users->get();
		return json_response(200, __('User.get_data'), new UserCollection($users));
	}
	
	public function save(UserRequest $request)
	{
		$users = CUser::all();
		tenancy()->end();
	
		return $this->user_service->saveUser($request);
	}
	
	public function updateProfilePic(UserRequest $request)
	{	
		$user = CUser::findByUUID($request->user_id);
		$file = new File;
		$old_dps = $user->profile_pic;
		$file->removeImage($user, $old_dps);
		
		if($request->image) {
			$type = 'profile_pic';
			$file = $file->createImage($request, $type); 
			$user->files()->attach([$file->id]);
		}
		return json_response(200, __('User.profile_pic'), new ProfilePicResource($file));	
	}
	
	public function show(UserRequest $request)
	{
		$user = CUser::whereUUID($request->uuid)->with('facilities','permissions')->first();
		return json_response(200, __('User.get_single'), new UserResource($user));
	}
	
	public function update(UserRequest $request)
	{	
		return $this->user_service->updateUser($request);
	}
	
	public function multiDelete(UserRequest $request)
	{
		return $this->user_service->deleteUsers($request);
	}
}
