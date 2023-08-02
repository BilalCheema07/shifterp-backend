<?php

namespace App\Http\Resources\Tenant\Inventory\Kit;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KitCollection extends ResourceCollection
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
				'uuid' => $item->uuid,
				'name' => $item->name,
				'description' => $item->description,
				'customer' => new CustomerResource($item->customer)
			];
		}
		return $data;
	}
}
