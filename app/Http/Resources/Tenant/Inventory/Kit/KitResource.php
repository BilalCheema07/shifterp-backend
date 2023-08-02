<?php

namespace App\Http\Resources\Tenant\Inventory\Kit;

use Illuminate\Http\Resources\Json\JsonResource;

class KitResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'uuid' => $this->uuid,
			'name' => $this->name,
			'description' => $this->description,
			'customer' => new CustomerResource($this->customer),
			'kit_products' => KitProductResource::collection($this->kitProducts->where('parent_id',0))
		];
	}
}
