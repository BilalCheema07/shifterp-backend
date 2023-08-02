<?php

namespace App\Http\Requests\Tenant\SmartSchedule;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		switch (last(request()->segments())) {
			case "add-blend-order":
				return $this->addBlendOrder();
			case "add-production-order":
				return $this->addProductionOrder();
			case "add-receiving-order":
				return $this->addReceivingOrder();
			case "add-shipping-order":
				return $this->addShippingOrder();
			case "possible-connected-orders":
				return $this->possibleConnectedOrders();
			case "connected-orders":
				return $this->connectedOrders();
			case "import-csv":
				return $this->importCSV();
			case "order-detail":
				return $this->orderDetail();
			case "cancel-detail":
				return $this->cancelDetail();
			default:
				return $this->list();
		}
	}

	public function possibleConnectedOrders()
	{
		return [
			"type" => "required|in:blend,production,receiving,shipping",
			"customer_id" => "required|exists:customers,uuid",
		];
	}

	protected function connectedOrders()
	{
		return [
			"order_id" => "required|exists:orders,uuid"
		];
	}

	protected function addBlendOrder()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"connected_order_id" => "nullable|exists:orders,uuid",
			"kit_id" => "required|exists:kits,uuid",
			"unit_id" => "required|exists:units,uuid",
			"driver1_id" => "required|exists:drivers,uuid",
			"driver2_id" => "required|exists:drivers,uuid",
			"date" => "required",
			"time" => "required",
			"po_notes" => "nullable|string",
			"notes" => "nullable|string",
			"quantity" => "required|integer",
			"is_remote_pick" => "required|in:0,1"
		];
	}

	protected function addProductionOrder()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"connected_order_id" => "nullable|exists:orders,uuid",
			"kit_id" => "required|exists:kits,uuid",
			"unit_id" => "required|exists:units,uuid",
			"driver1_id" => "required|exists:drivers,uuid",
			"driver2_id" => "required|exists:drivers,uuid",
			"date" => "required",
			"time" => "required",
			"po_notes" => "nullable|string",
			"notes" => "nullable|string",
			"quantity" => "required|integer",
			"is_remote_pick" => "required|in:0,1",
			"is_allergen_pick" => "required|in:0,1"
		];
	}


	protected function addShippingOrder()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"po_number" => "nullable",
			"release_number" => "nullable",
			"date" => "required",
			"time" => "required",

			"shipper_id" => "required|exists:shippers,uuid",
			"ship_to_id" => "required|exists:ship_tos,uuid",
			"driver1_id" => "required|exists:drivers,uuid",
			"driver2_id" => "required|exists:drivers,uuid",

			"stack_type_id" => "required|exists:stack_types,uuid",
			"charge_type_id" => "required|exists:charge_types,uuid",
			"po_notes" => "nullable|string",
			"notes" => "nullable|string",
			"is_remote_pick" => "required|in:0,1",
			"is_allergen_pick" => "required|in:0,1",
			"is_customer_called" => "required|in:0,1"
		];
	}


	protected function addReceivingOrder()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"connected_order_id" => "nullable|exists:orders,uuid",
			"date" => "required",
			"time" => "required",
			"received_from" => "required|exists:customers,uuid",
			
			"po_number" => "nullable",
			"release_number" => "nullable",
			"shipper_id" => "required|exists:shippers,uuid",
			"driver1_id" => "required|exists:drivers,uuid",
			"driver2_id" => "required|exists:drivers,uuid",
			
			"po_notes" => "nullable|string",
			"notes" => "nullable|string",
			
			"unit_id" => "required|exists:units,uuid",
			"quantity" => "required|integer",
		];
	}

	protected function importCSV()
	{
		return [
			'file' => 'required|mimes:csv,txt,xls,xlsx'
		];
		
	}

	protected function list()
	{
		return [
			'duration'	=> 'nullable',
			'date'		=> 'nullable',
			'type'		=> 'nullable|string',
			'status'	=> 'nullable|string',
		];
	}

	protected function orderDetail()
	{
		return [
			"order_uuid" => "required|exists:orders,uuid"
		];
	}

	protected function cancelOrder()
	{
		return [
			"order_uuid" => "required|exists:orders,uuid"
		];
	}
}
