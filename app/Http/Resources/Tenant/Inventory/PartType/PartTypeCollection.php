<?php

namespace App\Http\Resources\Tenant\Inventory\PartType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PartTypeCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return PartTypeResource::collection($this->collection);
	}
}
