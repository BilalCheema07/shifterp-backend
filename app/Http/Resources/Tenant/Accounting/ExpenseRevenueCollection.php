<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseRevenueCollection extends ResourceCollection
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
                "date" => @$item->date,
                "amount" => @$item->amount,
                "notes" => @$item->notes,
                "revenue_type" => [
                    "uuid" => @$item->revenueType->uuid,
                    "name" => @$item->revenueType->name,
                ],
                "shift" => [
                    "uuid" => @$item->shift->uuid,
                    "name" => @$item->shift->name,
                ],
                "revenue_item" => [
                    "uuid" => @$item->revenueItem->uuid,
                    "name" => @$item->revenueItem->name,
                ],
                'customer' => [
                    "uuid" => @$item->customer->uuid,
                    "name" => @$item->customer->name,
                ],
                'facility' => [
                    "uuid" => @$item->facility->uuid,
                    "name" => @$item->facility->name,
                ]
			];
		}
		return $data;
	}
}
