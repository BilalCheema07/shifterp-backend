<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SowResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'url' => $this->url,
            'billing_date' => $this->billing_date,
        ];
    }
}
