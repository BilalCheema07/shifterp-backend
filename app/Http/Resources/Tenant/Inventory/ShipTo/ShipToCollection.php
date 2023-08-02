<?php

namespace App\Http\Resources\Tenant\Inventory\ShipTo;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Tenant\PrimaryContactResource;


class ShipToCollection extends ResourceCollection
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
				'uuid'          => $item->uuid,
				'customer_name' => $item->customer->name,
				'customer_uuid' => $item->customer->uuid,
				'name'          => $item->name,
				'external_id'   => $item->external_id,
				'address1'       => $item->address1,
				'address2'       => $item->address2,
				'city'          => $item->city,
				'state'         => $item->state,
				'zip_code'      => $item->zip_code,
				'status'        => $item->status,
				'primary_contacts' => new PrimaryContactResource($item->primaryContact)
			];
		}
		return $data;
	}
}
