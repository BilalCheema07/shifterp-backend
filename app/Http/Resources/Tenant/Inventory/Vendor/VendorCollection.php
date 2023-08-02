<?php

namespace App\Http\Resources\Tenant\Inventory\Vendor;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Tenant\PrimaryContactResource;


class VendorCollection extends ResourceCollection
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
				'company_name'  => $item->company_name,
				'dba_name'  	=> $item->dba_name,
				'city'          => $item->city,
				'state'         => $item->state,
				'zip_code'      => $item->zip_code,
				'address'       => $item->address,
				'status'        => $item->status,
				'primary_contacts' => new PrimaryContactResource($item->primaryContact)
			];
		}
		return $data;
	}
}
