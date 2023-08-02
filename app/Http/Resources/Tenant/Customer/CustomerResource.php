<?php

namespace App\Http\Resources\Tenant\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Tenant\PrimaryContactResource;

class CustomerResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return  [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'name' => $this->name,
			'code' => $this->code,
			'city' => $this->city,
			'state' => $this->state,
			'status' => $this->status,
			'zip_code' => $this->zip_code,
			'shipping_pick_logic' => @$this->shipping_pick_logic,
			'production_pick_logic' => @$this->production_pick_logic,
			'min_charge' => @$this->min_charge,
			'lot_number' => @$this->lot_number,
			'lot_id1' => @$this->lot_id1,
			'lot_id2' => @$this->lot_id2,
			'receive_date' => @$this->receive_date,
			'production_date' => @$this->production_date,
			'expiration_date' => @$this->expiration_date,
			'billed_date' => @$this->billed_date,
			'show_unit_of_count' => @$this->show_unit_of_count,
			'group_by_item' => @$this->group_by_item,
			'group_by_lot_number' => @$this->group_by_lot_number,
			'group_by_lot_id1' => @$this->group_by_lot_id1,
			'group_by_lot_id2' => @$this->group_by_lot_id2,
			'primary_contacts' => new PrimaryContactResource($this->primaryContact),
			'facilities' => $this->facilities(@$this->facilities),
		];
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
