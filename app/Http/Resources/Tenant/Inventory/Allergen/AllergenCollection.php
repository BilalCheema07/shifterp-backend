<?php

namespace App\Http\Resources\Tenant\Inventory\Allergen;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AllergenCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return AllergenResource::collection($this->collection);
	}
}
