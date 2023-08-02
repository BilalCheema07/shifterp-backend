<?php

namespace App\Http\Resources\Tenant\Inventory\ShipTo;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tenant\PrimaryContactResource;


class ShipToResource extends JsonResource
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
            'name'          => $this->name,
            'external_id'   => $this->external_id,
            'customer'      => 
                                [ 
                                    'uuid' => $this->customer->uuid,
                                    'name' => $this->customer->name,
                                    'code' => $this->customer->code,
                                ],
            'address1'       => $this->address1,
            'address2'       => $this->address2,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => $this->zip_code,
            'status'        => $this->status,
            
            'primary_contacts' => new PrimaryContactResource($this->primaryContact)

		];
    }
}
