<?php

namespace App\Http\Resources\Tenant\Facility;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityUserResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'uuid' => $this->uuid,
			'username' => $this->username,
			'user_role' => $this->roles[0]->name,
			'email' => $this->email,
			'profile_pic' => @$this->profile_pic[0],
			'contact_number' => $this->phone
		];
	}
}
