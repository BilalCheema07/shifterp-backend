<?php

namespace App\Http\Resources\Tenant\Inventory\Product;

use App\Http\Resources\Tenant\Unit\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductUnitResource extends JsonResource
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
			"unit_of_stock" => new UnitResource($this->stockUnit),
			"unit_of_order" => new UnitResource($this->orderUnit),
			"unit_of_purchase" => $this->unit_of_purchase ? new UnitResource($this->purchaseUnit) : [],
			"unit_of_count" => new UnitResource($this->countUnit),
			"unit_of_package" => new UnitResource($this->packageUnit),
			"unit_of_sell" => new UnitResource($this->sellUnit),
			"unit_of_assembly" => $this->unit_of_assembly ? new UnitResource($this->assemblyUnit) : [],
			"variable_unit1" => $this->variable_unit1 ? new UnitResource($this->varUnit1) : [],
			"variable_unit2" => $this->variable_unit2 ? new UnitResource($this->varUnit2) : [],
			"convert_to_unit1" => new UnitResource($this->conUnit1),
			"convert_to_unit2" => new UnitResource($this->conUnit2),
			"convert_to_unit3" => $this->convert_to_unit3 ? new UnitResource($this->conUnit3) : [],
			"unit1_multiplier" => $this->unit1_multiplier,
			"unit2_multiplier" => $this->unit2_multiplier,
			"unit3_multiplier" => $this->unit3_multiplier,
			"item_gross_weight" => $this->item_gross_weight
		];
	}
}
