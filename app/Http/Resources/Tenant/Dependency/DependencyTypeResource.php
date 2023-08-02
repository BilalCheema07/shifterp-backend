<?php

namespace App\Http\Resources\Tenant\Dependency;

use Illuminate\Http\Resources\Json\JsonResource;

class DependencyTypeResource extends JsonResource
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
			"uuid" => $this->uuid,
			"name" => $this->name,
			"slug" => $this->slug,
			"dependencies" => new DependencyCollection($this->dependencies),
		];
    }
}
