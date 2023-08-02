<?php

namespace App\Http\Resources\Tenant\Dependency;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DependencyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return DependencyResource::collection($this->collection);
    }
}
