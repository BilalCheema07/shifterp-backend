<?php

namespace App\Http\Resources\Tenant\SmartSchedule;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
	/**
	* Transform the resource into an array.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	*/
	public function toArray($request)
	{
		if ($this->type == 'production') {
			return $this->productionOrder($this);
		} elseif ($this->type == 'blend') {
			return $this->blendOrder($this);
		} elseif ($this->type == 'shipping') {
			return $this->shippingOrder($this);
		} else {
			return $this->receivingOrder($this);
		}
	}
	
	//Production Order resource
	private function productionOrder()
	{
		return [
			"date"      => @$this->date,
			"time"      => @$this->time,
			"type"      => @$this->type,
			"status"    => @$this->status,
			"po_notes"  => @$this->po_notes,
			"notes"     => @$this->notes,
			"customer"  => [ 
				"uuid"  => @$this->customer->uuid,
				"name"  => @$this->customer->name,
				"code"  => @$this->customer->code,
			],
			"driver1"   => [
				'uuid'      => @$this->drivers[0]->uuid,
				'name'      => @$this->drivers[0]->name,
			],
			"driver2"   => [
				'uuid'      => @$this->drivers[1]->uuid,
				'name'      => @$this->drivers[1]->name,
			],
			'production_order' => [
				'quantity' => @$this->productionOrder->quantity,
				'is_remote_pick' => @$this->productionOrder->is_remote_pick,
				'unit' => @$this->productionOrder->unit->name,
				'kit' => [
					'uuid'          =>  @$this->productionOrder->kit->uuid,
					'name'          =>  @$this->productionOrder->kit->name,
					'description'   =>  @$this->productionOrder->kit->description,
					'kit_products'  => new KitProductsCollection($this->productionOrder->kit->products),
				]
			]
		];
	}

	//Blend Order resource
	private function blendOrder()
	{
		return [
			"date"      => @$this->date,
			"time"      => @$this->time,
			"type"      => @$this->type,
			"status"    => @$this->status,
			"po_notes"  => @$this->po_notes,
			"notes"     => @$this->notes,
			"customer"  => [ 
				"uuid"  => @$this->customer->uuid,
				"name"  => @$this->customer->name,
				"code"  => @$this->customer->code,
			],
			"driver1"   => [
				'uuid'      => @$this->drivers[0]->uuid,
				'name'      => @$this->drivers[0]->name,
			],
			"driver2"   => [
				'uuid'      => @$this->drivers[1]->uuid,
				'name'      => @$this->drivers[1]->name,
			],
			'blendOrder' =>
			[
				'quantity' => @$this->blendOrder->quantity,
				'is_remote_pick' => @$this->blendOrder->is_remote_pick,
				'unit' => @$this->blendOrder->unit->name,
				'kit' => [
					'uuid' => @$this->blendOrder->kit->uuid,
					'name' => @$this->blendOrder->kit->name,
					'description' => @$this->blendOrder->kit->description,
					'products' => new KitProductsCollection($this->blendOrder->kit->products),
				],
			]
		];
	}

	//Shipping Order resource
	private function shippingOrder()
	{
		return [
			"date"      => @$this->date,
			"time"      => @$this->time,
			"type"      => @$this->type,
			"status"    => @$this->status,
			"po_notes"  => @$this->po_notes,
			"notes"     => @$this->notes,
			"po_number" => @$this->po_number,
			"release_number" => @$this->release_number,
			"customer"  => [ 
				"uuid"  => @$this->customer->uuid,
				"name"  => @$this->customer->name,
				"code"  => @$this->customer->code,
			],
			"driver1"   => [
				'uuid'      => @$this->drivers[0]->uuid,
				'name'      => @$this->drivers[0]->name,
			],
			"driver2"   => [
				'uuid'      => @$this->drivers[1]->uuid,
				'name'      => @$this->drivers[1]->name,
			],
			'shipping_order' => [
				"quantity" => @$this->shippingOrder->quantity,
				"is_remote_pick" => @$this->shippingOrder->is_remote_pick,
				"is_allergen_pick" => @$this->shippingOrder->is_allergen_pick,
				"is_customer_called" => @$this->shippingOrder->is_customer_called,
				"shipper" => [
					"uuid" => @$this->shippingOrder->shipper->uuid,
					"name" => @$this->shippingOrder->shipper->shipper_name,
				],
				
				"shipTo" => [
					"uuid" => @$this->shippingOrder->shipTo->uuid,
					"name" => @$this->shippingOrder->shipTo->name,
				],
				
				"stackType" => [
					"uuid" => @$this->shippingOrder->stackType->uuid,
					"name" => @$this->shippingOrder->stackType->name,
				],
				
				"chargeType" => [
					"uuid" => @$this->shippingOrder->chargeType->uuid,
					"name" => @$this->shippingOrder->chargeType->name,
				],
			]
		];
	}

	//Receiving Order
	private function receivingOrder()
	{
		return [
			"date"      => @$this->date,
			"time"      => @$this->time,
			"type"      => @$this->type,
			"status"    => @$this->status,
			"po_notes"  => @$this->po_notes,
			"notes"     => @$this->notes,
			"po_number" => @$this->po_number,
			"release_number" => @$this->release_number,
			"customer"  => [ 
				"uuid"  => @$this->customer->uuid,
				"name"  => @$this->customer->name,
				"code"  => @$this->customer->code,
			],
			"driver1"   => [
				'uuid'      => @$this->drivers[0]->uuid,
				'name'      => @$this->drivers[0]->name,
			],
			"driver2"   => [
				'uuid'      => @$this->drivers[1]->uuid,
				'name'      => @$this->drivers[1]->name,
			],
			'receiving_order' => [
				"quantity" => @$this->receivingOrder->quantity,
				"shipper" => [
					"uuid" => @$this->receivingOrder->shipper->uuid,
					"name" => @$this->receivingOrder->shipper->shipper_name,
				],
				
				"unit" => [
					"uuid" => @$this->receivingOrder->unit->uuid,
					"name" => @$this->receivingOrder->unit->name,
				],
				
				"receivedFrom" => [
					"uuid" => @$this->receivingOrder->receivedFrom->uuid,
					"name" => @$this->receivingOrder->receivedFrom->name,
				],
			],
		];
	}
}

			