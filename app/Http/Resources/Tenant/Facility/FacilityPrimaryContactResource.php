<?php

namespace App\Http\Resources\Tenant\Facility;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityPrimaryContactResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->full_name,
			'email' => $this->email,
			'phone' => $this->phone,
        ];
    }
}
