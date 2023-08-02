<?php

namespace App\Http\Resources\Tenant\Inventory\Location;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationCollection extends ResourceCollection
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

        // return parent::toArray($request);
    }
    
	private function getData()
	{
		$data = [];
		foreach ($this->collection as $item) {
			$data[] = [
				'uuid' => $item->uuid,
				'name' => $item->name,
				'barcode' => $item->barcode,
				'custom_capacity' => $item->custom_capacity,
				'is_remote_pick' => $item->is_remote_pick,
				'is_allergen_pick' => $item->is_allergen_pick,
				'is_tall_location' => $item->is_tall_location,
				'status' => $item->status,
			];
		}
		return $data;
	}
}
