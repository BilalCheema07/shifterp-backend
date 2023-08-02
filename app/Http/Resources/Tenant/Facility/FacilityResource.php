<?php

namespace App\Http\Resources\Tenant\Facility;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'uuid' => $this->uuid,
			'name' => $this->name,
			'status' => $this->status,
			'address' => $this->address,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip_code,
			'status' => $this->status,
			'office_phone' => $this->office_phone,
			'profile_pic' => @$this->displayPic[0]->url,
			'users'  =>  FacilityUserResource::collection(@$this->users),
			'primary_contact' => new FacilityPrimaryContactResource($this->primaryContact),
		];
	}
}
