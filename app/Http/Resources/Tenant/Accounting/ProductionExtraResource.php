<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductionExtraResource extends JsonResource
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
			"uuid"              => $this->uuid,
			"name"              => $this->name,
			"amount"            => $this->amount,
			"direct_material"    => $this->direct_material,
			"status"            => $this->status,
			"unit" => [
				"uuid" => $this->unit->uuid,
				"name" => $this->unit->name,
			]
		];
	}
}
	