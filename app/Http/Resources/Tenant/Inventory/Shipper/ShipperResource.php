<?php

namespace App\Http\Resources\Tenant\Inventory\Shipper;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tenant\PrimaryContactResource;


class ShipperResource extends JsonResource
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
            'uuid'          => $this->uuid,
            'shipper_name'  => $this->shipper_name,
            'shipper_code'  => $this->shipper_code,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => $this->zip_code,
            'external_id'   => $this->external_id,
            'address'       => $this->address,
            'status'        => $this->status,
            'primary_contacts' => new PrimaryContactResource($this->primaryContact)

		];
    }
}
