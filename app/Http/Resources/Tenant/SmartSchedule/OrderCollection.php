<?php

namespace App\Http\Resources\Tenant\SmartSchedule;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
	public function toArray($request)
	{
		return  $this->getData();
	}

	protected function getData(){
		$data = [];
		foreach ($this->collection as $item) {
			if($item->type == 'blend'){
				 $data[] = $this->blendOrderDetail($item);
			} 
			elseif ($item->type =='production') {
				$data[] = $this->productionOrderDetail($item);
			}
			elseif ($item->type =='receiving') {
				$data[] = $this->receivingOrderDetail($item);
			}
			else{
				$data[] = $this->shippingOrderDetail($item);
			}
		}
		return $data;
	}

	public function blendOrderDetail($item)
	{
		return [
			'uuid' => $item->uuid,
			'type' => @$item->type,
			'date' => @$item->date,
			'time' => @$item->time,
            'schedule_id' => @$item->schedule_id,
			'has_connected_orders' => count($item->connectedChildOrders) > 0 ? 1 : 0,
			'driver1' => @$item->drivers[0]->name,
			'driver2' => @$item->drivers[1]->name,
			'po_number' => @$item->po_number,
			'release_number' => @$item->release_number,
			'po_notes' => @$item->po_notes,
			'notes' => @$item->notes,
			'status' => $item->status,
			'updated_by' => @$item->updated_by,
			'updated_at' => @$item->updated_at,
			'customer' => [
				'uuid' => $item->customer->uuid,
				'code' => $item->customer->code,
				'name' => $item->customer->name,
			],
			'blendOrder' =>
			[
				'quantity' => @$item->blendOrder->quantity,
				'is_remote_pick' => @$item->blendOrder->is_remote_pick,
				'unit' => @$item->blendOrder->unit->name,
				'kit' => [
					'kit_uuid'  =>  @$item->blendOrder->kit->uuid,
					'kit_name'  =>  @$item->blendOrder->kit->name,
					'kit_description'  =>  @$item->blendOrder->kit->description,
				]
			]

		];
	}

	public function productionOrderDetail($item)
	{
		return [
			'uuid' => $item->uuid,
			'type' => @$item->type,
			'date' => @$item->date,
			'time' => @$item->time,
            'schedule_id' => @$item->schedule_id,
			'has_connected_orders' => count($item->connectedChildOrders) > 0 ? 1 : 0,
			'driver1' => @$item->drivers[0]->name,
			'driver2' => @$item->drivers[1]->name,
			'po_number' => @$item->po_number,
			'release_number' => @$item->release_number,
			'po_notes' => @$item->po_notes,
			'notes' => @$item->notes,
			'status' => @$item->status,
			'updated_by' => @$item->updated_by,
			'updated_at' => @$item->updated_at,
			'customer' => [
				'uuid' => $item->customer->uuid,
				'code' => $item->customer->code,
				'name' => $item->customer->name,
			],
			'production_order' => [
				'quantity' => @$item->ProductionOrder->quantity,
				'is_remote_pick' => @$item->ProductionOrder->is_remote_pick,
				'unit' => @$item->ProductionOrder->unit->name,
				'kit' => [
					'kit_uuid'          =>  @$item->productionOrder->kit->uuid,
					'kit_name'          =>  @$item->productionOrder->kit->name,
					'kit_description'   =>  @$item->productionOrder->kit->description,
				]            
			]

		];
	}

	public function receivingOrderDetail($item)
	{
		return [
			'uuid' => $item->uuid,
			'type' => @$item->type,
			'date' => @$item->date,
			'time' => @$item->time,
            'schedule_id' => @$item->schedule_id,
			'has_connected_orders' => count($item->connectedChildOrders) > 0 ? 1 : 0,
			'driver1' => @$item->drivers[0]->name,
			'driver2' => @$item->drivers[1]->name,
			'po_number' => @$item->po_number,
			'release_number' => @$item->release_number,
			'po_notes' => @$item->po_notes,
			'notes' => @$item->notes,
			'status' => @$item->status,
			'updated_by' => @$item->updated_by,
			'updated_at' => @$item->updated_at,
			'customer' => [
				'uuid' => $item->customer->uuid,
				'code' => $item->customer->code,
				'name' => $item->customer->name,
			],
			'receiving_order' => [
				'quantity' => @$item->receivingOrder->quantity,
				'shipper' => @$item->receivingOrder->shipper->shipper_name,
				'unit' => @$item->receivingOrder->unit->name,
				'received_from' => @$item->receivingOrder->receivedFrom->name,
			]

		];

	}

	public function shippingOrderDetail($item)
	{
		return [
			'uuid' => $item->uuid,
			'type' => @$item->type,
			'date' => @$item->date,
			'time' => @$item->time,
            'schedule_id' => @$item->schedule_id,
			'has_connected_orders' => count($item->connectedChildOrders) > 0 ? 1 : 0,
			'driver1' => @$item->drivers[0]->name,
			'driver2' => @$item->drivers[1]->name,
			'po_number' => @$item->po_number,
			'release_number' => @$item->release_number,
			'po_notes' => @$item->po_notes,
			'notes' => @$item->notes,
			'status' => @$item->status,
			'updated_by' => @$item->updated_by,
			'updated_at' => @$item->updated_at,
			'customer' => [
				'uuid' => $item->customer->uuid,
				'code' => $item->customer->code,
				'name' => $item->customer->name,
			],
			'shipping_order' => [
				'shipper' => @$item->shippingOrder->shipper->shipper_name,
				'shipTo' => @$item->shippingOrder->shipTo->name,
				'stackType' => @$item->shippingOrder->stackType->name,
				'chargeType' => @$item->shippingOrder->chargeType->name,
				'remote_pick' => @$item->shippingOrder->is_remote_pick,
				"is_remote_pick" => @$item->shippingOrder->is_remote_pick,
				"is_allergen_pick" => @$item->shippingOrder->is_allergen_pick,
				"is_customer_called" => @$item->shippingOrder->is_customer_called,
			]
		];
	}
}
