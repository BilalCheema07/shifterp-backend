<?php

namespace App\Http\Resources\Tenant\Dependency;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DependencyTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->getData();
        // return parent::toArray($request);
    }

	private function getData()
	{   
		$data = [];
		foreach ($this->collection as $type) {
			$data[] =[
				'uuid' => $type->uuid,
				'name' => $type->name,
				'slug' => $type->slug,
				'modules' => $type->modules
			];
		}
		return $data;
	}
}
