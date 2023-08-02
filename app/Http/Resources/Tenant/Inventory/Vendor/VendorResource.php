<?php

namespace App\Http\Resources\Tenant\Inventory\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tenant\PrimaryContactResource;


class VendorResource extends JsonResource
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
            'company_name'  => $this->company_name,
            'dba_name'      => $this->dba_name,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip_code'      => $this->zip_code,
            'address'       => $this->address,
            'status'        => $this->status,
            'primary_contact' => new PrimaryContactResource($this->primaryContact)

		];
    }
}
