<?php

namespace App\Http\Resources\Tenant\Inventory\Kit;

use App\Http\Resources\Tenant\Unit\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAlternativeResource extends JsonResource
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
			'uuid'    		=>  $this->uuid,
			'priority'	    =>  $this->priority,
			'parent_id'	    =>  $this->parent_id,
			'amount'	    =>  $this->amount,
			'unit'	        =>  new UnitResource($this->unit),
			'product'    	=>  new ProductResource($this->product),
			'part_type'		=>  new PartTypeResource($this->partType),
		];
	}
}
