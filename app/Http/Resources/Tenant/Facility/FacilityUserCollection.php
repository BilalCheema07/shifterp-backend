<?php

namespace App\Http\Resources\Tenant\Facility;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FacilityUserCollection extends ResourceCollection
{
	public function toArray($request)
	{
		return FacilityUserResource::collection($this->collection);
	}
}
