<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionsResource extends JsonResource
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
            'uuid'                  => $this->uuid,
            'name'                  => $this->name,
            'user_limit'            => $this->user_limit,
            'facility_limit'        => $this->facility_limit,
            'price_per_license'     => $this->price_per_license,
        ];
    }
}
