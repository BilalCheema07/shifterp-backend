<?php

namespace App\Http\Resources\Tenant\Inventory\Shipper;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Tenant\PrimaryContactResource;


class ShipperCollection extends ResourceCollection
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
				'shipper_name'  => $item->shipper_name,
				'shipper_code'  => $item->shipper_code,
				'city'          => $item->city,
				'state'         => $item->state,
				'zip_code'      => $item->zip_code,
				'external_id'   => $item->external_id,
				'address'       => $item->address,
				'status'        => $item->status,
				'primary_contacts' => new PrimaryContactResource($item->primaryContact)
			];
		}
		return $data;
	}
}
