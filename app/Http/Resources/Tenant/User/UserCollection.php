<?php

namespace App\Http\Resources\Tenant\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
	public function toArray($request)
	{
		return [
			'users' => 	$this->getData(),
		];
	}
	
	private function getData()
	{   
		$data = [];
		foreach ($this->collection as $user) {
			$data[] = [
				'id' => $user->id,
				'uuid' => $user->uuid,
				'full_name' => $user->full_name,
				'fname' => $user->fname,
				'lname' => $user->lname,
				'username' => $user->username,
				'phone' => $user->phone,
				'email' => $user->email,
				'address' => $user->address,
				'city' => $user->city,
				'zip_code' => $user->zip_code,
				'state' => $user->state,
				'status' => $user->status,
				'role'  => @$user->roles[0]->name,
				'role_uuid'  => @$user->roles[0]->uuid,
				'permission' => @$user->permissions,
				'profile_pic' => @$user->profile_pic[0],
				'facilities'  => @$user->facilities
			];
		}
		return $data;
	}
}
