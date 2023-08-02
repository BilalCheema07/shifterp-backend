<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingResource extends JsonResource
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
			"uuid" => $this->uuid,
			"name" => $this->name,
			"lot_number" => $this->lot_number,
			"lot_id1" => $this->lot_id1,
			"lot_id2" => $this->lot_id2,
			"grace_period" => $this->grace_period,
			"price_per_unit" => $this->price_per_unit,
			"min_charge" => $this->min_charge,
			"status" => $this->status,
			"customer" => [
				"uuid" => $this->customer->uuid,
				"name" => $this->customer->name,
			],
			"category" => [
				"uuid" => $this->category->uuid,
				"name" => $this->category->name,
			],
			"product" => [
				"uuid" => $this->product->uuid,
				"name" => $this->product->name,
			],
			"pricing_type" => [
				"uuid" => $this->pricingType->uuid,
				"name" => $this->pricingType->name,
			],
			"charge_type" => [
				"uuid" => $this->chargeType->uuid,
				"name" => $this->chargeType->name,
			],
			"unit" => [
				"uuid" => $this->unit->uuid,
				"name" => $this->unit->name,
			],
		];
	}
}
