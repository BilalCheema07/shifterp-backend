<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CUserCollection extends ResourceCollection
{
	/**
	* Transform the resource collection into an array.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	*/
	public function toArray($request)
	{
		return [
			'data' => $this->getData()
		];
	}
	
	private function getData()
	{   
		$data = [];
		foreach ($this->collection as $users) {
			$data[] =[
				'id' => $users->id,
				'username' => $users->username,
				'email' => $users->email,
				'phone' => $users->phone,
				'status' => $users->status
			];
		}
		return $data;
	}
}