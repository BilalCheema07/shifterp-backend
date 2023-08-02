<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PricingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->getData();
    }
    
	private function getData()
	{
		$data = [];
		foreach ($this->collection as $item) {
			$data[] = [
                "uuid" => @$item->uuid,
                "name" => @$item->name,
                "lot_number" => @$item->lot_number,
                "lot_id1" => @$item->lot_id1,
                "lot_id2" => @$item->lot_id2,
                "grace_period" => @$item->grace_period,
                "price_per_unit" => @$item->price_per_unit,
                "min_charge" => @$item->min_charge,
                "status" => @$item->status,
                "customer" => [
                    "uuid" => @$item->customer->uuid,
                    "name" => @$item->customer->name,
                    "code" => @$item->customer->code,
                ],
                "category" => [
                    "uuid" => @$item->category->uuid,
                    "name" => @$item->category->name,
                ],
                "product" => [
                    "uuid" => @$item->product->uuid,
                    "name" => @$item->product->name,
                ],
                "pricing_type" => [
                    "uuid" => @$item->pricingType->uuid,
                    "name" => @$item->pricingType->name,
                ],
                "charge_type" => [
                    "uuid" => @$item->chargeType->uuid,
                    "name" => @$item->chargeType->name,
                ],
                "unit" => [
                    "uuid" => @$item->unit->uuid,
                    "name" => @$item->unit->name,
                ],
			];
		}
		return $data;
	}
}
