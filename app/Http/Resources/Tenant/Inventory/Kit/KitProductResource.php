<?php

namespace App\Http\Resources\Tenant\Inventory\Kit;
use App\Http\Resources\Tenant\Unit\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class KitProductResource extends JsonResource
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
				'amount' => $this->amount,
				'unit' => new UnitResource($this->unit),
				'part_type' => new PartTypeResource($this->partType),
				'product' => new ProductResource($this->product),
				'product_alternative' => ProductAlternativeResource::collection($this->alternatives)
			];
		 
	}
}
