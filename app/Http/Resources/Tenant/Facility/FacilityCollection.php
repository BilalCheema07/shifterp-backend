<?php

namespace App\Http\Resources\Tenant\Facility;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FacilityCollection extends ResourceCollection
{
	public function toArray($request)
	{
		return 
			 $this->getData();
	}
	
	private function getData()
	{   
		$data = [];
		foreach ($this->collection as $facility) {
			$data[] =[
				'id' => $facility->id,
				'uuid' => $facility->uuid,
				'name' => $facility->name,
				'status' => $facility->status,
				'address' => $facility->address,
				'city' => $facility->city,
				'state' => $facility->state,
				'date_of_creation' => $facility->created_at,
				'zip'  => $facility->zip_code,
				'office_phone' => $facility->office_phone,
				'profile_pic' => @$facility->displayPic[0]->url,
				'users'  =>  FacilityUserResource::collection(@$facility->users),
				'primary_contact' => new FacilityPrimaryContactResource($facility->primaryContact)
 			];
		}
		return $data;
	}
}
