<?php

namespace App\Http\Resources\Tenant\Unit;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UtypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return UtypeResource::collection($this->collection);
    }
}
