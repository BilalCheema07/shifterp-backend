<?php

namespace App\Http\Resources\Tenant\Inventory\PartType;

use Illuminate\Http\Resources\Json\JsonResource;

class PartTypeResource extends JsonResource
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
			'name' => $this->name
		];
	}
}
