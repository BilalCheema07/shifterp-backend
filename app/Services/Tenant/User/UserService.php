<?php 
namespace App\Services\Tenant\User;

use App\Http\Resources\Tenant\User\UserResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant\{User as CUser, Facility, Role, Permission};

class UserService 
{
	public function saveUser($request)
	{
		$password = strtolower(Str::random(8));
		$tenant_id = auth()->user()->tenant_id;
		
		tenancy()->end();
		
		// checking for duplication
		$auth_user = User::where('email', $request->email)->orWhere('username', $request->username)->first();
		if ($auth_user) {
			if ($auth_user->email == $request->email) {
				return json_response(403, __('User.email_error'));
			} else {
				return json_response(403, __('User.username_error'));
			}
		}
		
		tenancy()->initialize($tenant_id);
		$role = Role::findByUUID($request->role_id);
		
		$user = CUser::create(array_merge($request->all(), ['password' => Hash::make($password)]));
		if (!$user) {
			return json_response(500, __('User.error'));
		}
		
		tenancy()->end();
		User::create([
			'username' => $user->username,
			'email' => $user->email,
			'password' => $user->password,
			'phone' => $user->phone,
			'tenant_user_id' => $user->id,
			'tenant_id' => $tenant_id,
			'role' => $role->slug
		]);
		tenancy()->initialize($tenant_id);
		
		$user->roles()->attach($role->id);
		
		if ((@$request->permission_ids) > 0) {
			$permission_valid_ids = Permission::getByUUID(@$request->permission_ids)->pluck('id');
			$user->permissions()->attach($permission_valid_ids);
		}
		
		if ((@$request->facilities) > 0) {
			$facilities_valid_ids = Facility::getByUUID(@$request->facilities)->pluck('id');
			$user->facilities()->attach($facilities_valid_ids);
		}

		$data = ['user' => new UserResource($user), 'password' => $password];
		
		return json_response(200, __('User.added'), $data);	
	}

	public function updateUser($request)
	{
		$tenant_id = auth()->user()->tenant_id;
		
		$user = CUser::whereUUID($request->uuid)->with('roles', 'permissions')->first();
		
		tenancy()->end();
		// checking for duplication
		$auth_user = User::where('tenant_user_id', '!=', $user->id)
		->where(function ($query) use ($request) {
			$query->where('email', $request->email)
			->orWhere('username', $request->username);
		})->first();
		if ($auth_user) {
			if ($auth_user->email == $request->email) {
				return json_response(403, 'User.email_error');
			} else {
				return json_response(403, 'User.username_error');
			}
		}
		tenancy()->initialize($tenant_id);
		
		if (@$request->role_id) {
			$role = Role::findByUUID($request->role_id);
		}
		$prev_role_id = $user->roles[0]->id;
		$x = $y = 0;
		
		$user->fname = $request->fname;
		$user->lname = $request->lname;
		$user->phone = $request->phone;
		if ($user->email != $request->email) {
			$user->email = $request->email;
			$x = 1;
		}
		if ($user->username != $request->username) {
			$user->username = $request->username;
			$y = 1;
		}
		$user->address = $request->address;
		$user->city = $request->city;
		$user->zip_code = $request->zip_code;
		$user->state = $request->state;
		$user->status = $request->status;
		if(@$request->role_id){
			$user->job_title = $role->name;
		}
		$user->release_date = @$request->release_date;
		$user->save();
		
		if(@$request->role_id){
			$user->roles()->detach();
			$user->roles()->attach($role->id);
		}
		
		$user->permissions()->detach();
		$user->facilities()->detach();
		
		if ((@$request->permission_ids) > 0) {
			$permission_valid_ids = Permission::getByUUID(@$request->permission_ids)->pluck('id');
			$user->permissions()->attach($permission_valid_ids);
		}
		
		if ((@$request->facilities) > 0) {
			$facilities_valid_ids = Facility::getByUUID(@$request->facilities)->pluck('id');
			$user->facilities()->attach($facilities_valid_ids);
		}
		
		tenancy()->end();
		
		$auth_user = User::where('tenant_user_id', $user->id)->first();
		$auth_user->phone = $user->phone;
		if ($x) {
			$auth_user->email = $user->email;
		}
		if ($y) {
			$auth_user->username = $user->username;
		}
		$auth_user->save();
		
		tenancy()->initialize($tenant_id);
		
		$data = ['user'=> new UserResource($user)];
		return json_response(200, __('User.updated'), $data);	
	}

	public function deleteUsers($request)
	{
		$tenant_id = auth()->user()->tenant_id;
		$users = CUser::whereInUUID($request->ids)
		->where('id', '<>', auth()->user()->tenant_user_id)
		->get();
		$facility_admin = 0;
		if (count($users) > 0) {
			foreach ($users as $user) {
				$facility = Facility::where('admin_id',$user->id)->first();
				if($facility){
					$facility_admin = 1;
				}
			}

			if($facility_admin == 1){
				return json_response(403, __('User.delete_failed'));
			}
			foreach ($users as $user) {
				$user->facilities()->detach();
				$user->delete();
				
				tenancy()->end();
				
				$auth_user = User::where('email', $user->email)->first();
				if($auth_user){
					$auth_user->delete();
				}
				
				tenancy()->initialize($tenant_id);
			}
			return json_response(200, __('User.delete'));
		}
	}
}