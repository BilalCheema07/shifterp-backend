<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductionExtraCollection extends ResourceCollection
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
				"amount" => @$item->amount,
				"direct_material" => @$item->direct_material,
				"status" => @$item->status,
				"unit" => [
					"uuid" => @$item->unit->uuid,
					"name" => @$item->unit->name
				],
			];
		}
		return $data;
	}
}
