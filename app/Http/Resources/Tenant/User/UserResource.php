<?php

namespace App\Http\Resources\Tenant\User;

use App\Http\Requests\Tenant\FacilityRequest;
use App\Http\Resources\Tenant\Facility\FacilityCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tenant\Facility\Facility;
class UserResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'fname' => $this->fname,
			'lname' => $this->lname,
			'username' => $this->username,
			'phone' => $this->phone,
			'email' => $this->email,
			'address' => $this->address,
			'city' => $this->city,
			'zip_code' => $this->zip_code,
			'state' => $this->state,
			'status' => $this->status,
			'role'  => @$this->roles[0]->name,
			'role_uuid'  => @$this->roles[0]->uuid,
			'permission' => @$this->permissions,
			'profile_pic' => @$this->profile_pic[0],
			'facilities'  => (@$this->facilities) ? new FacilityCollection($this->facilities ) : @$this->facilities
		];
	}
}
