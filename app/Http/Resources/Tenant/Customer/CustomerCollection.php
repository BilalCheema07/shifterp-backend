<?php

namespace App\Http\Resources\Tenant\Customer;
use App\Http\Resources\Tenant\Customer\CustomerResource;
use App\Http\Resources\Tenant\PrimaryContactResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
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
	}
	
	private function getData()
	{
		$data = [];
		foreach ($this->collection as $item) {
			$data[] = [
				'uuid' => $item->uuid,
				'name' => $item->name,
				'code' => $item->code,
				'city' => $item->city,
				'state' => $item->state,
				'status' => $item->status,
				'primary_contacts' => new PrimaryContactResource($item->primaryContact),
				'facilities' => $this->facilities(@$item->facilities)
			];
		}
		return $data;
	}

	private function facilities($facilities)
	{
		$data = [];
		foreach ($facilities as $value) {
			$data[] = [
				"uuid" => $value->uuid,
				"name" => $value->name,
				"primary_contact" => [
					'email' => $value->primaryContact->email,
					'phone' => $value->primaryContact->phone,
				]
			];
		}
		return $data;
	}
}
