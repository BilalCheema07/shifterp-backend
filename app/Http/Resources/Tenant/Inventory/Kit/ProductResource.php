<?php

namespace App\Http\Resources\Tenant\Inventory\Kit;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
			'internal_name' => $this->internal_name,
			'internal_description' => $this->internal_description,
			'barcode' => $this->barcode,
			'universal_product_code' => $this->universal_product_code,
			'status' => $this->status,
		];
	}
}
