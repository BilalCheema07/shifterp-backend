<?php

namespace App\Http\Resources\Tenant\Inventory\Product;

use App\Http\Resources\Tenant\Unit\UnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShippingResource extends JsonResource
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
			'pallet_tie' => $this->pallet_tie,
			'kit_parent_cost' => $this->kit_parent_cost,
			'shelve_life' => $this->shelve_life,
			'safety_stock' => $this->safety_stock,
			'safety_stock_unit' => $this->safety_stock_unit ? new UnitResource($this->safetyUnit) : [],
			'par_level' => $this->par_level,
			'par_level_unit' => $this->par_level_unit ? new UnitResource($this->parUnit) : [],
			'minimum_blend_amount' => $this->minimum_blend_amount,
			'is_global' => $this->is_global,
			'is_kit_parent' => $this->is_kit_parent,
			'is_high_risk' => $this->is_high_risk,
			'cost_item' => $this->cost_item
		];
	}
}
