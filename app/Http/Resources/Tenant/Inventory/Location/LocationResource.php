<?php

namespace App\Http\Resources\Tenant\Inventory\Location;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'uuid'              => $this->uuid,
            'name'              => $this->name,
            'barcode'           => $this->barcode,
            'custom_capacity'   => $this->custom_capacity,
            'is_remote_pick'    => $this->is_remote_pick,
            'is_allergen_pick'  => $this->is_allergen_pick,
            'is_tall_location'  => $this->is_tall_location,
            'status'            => $this->status
		];
    }
}
