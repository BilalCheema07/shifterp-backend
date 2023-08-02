<?php 
namespace App\Services\Tenant\SmartSchedule;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{Order, ShippingOrder};

class ShippingOrderService
{
	public function addNewShippingOrder($request)
	{	
		DB::beginTransaction();
		try {
			$order = Order::createOrder($request->all(), $type = "shipping");

			ShippingOrder::create(array_merge([
				"shipper_id" =>  ShipperId($request->shipper_id),
				"ship_to_id" => ShipTo($request->ship_to_id),
				"stack_type_id" => stackTypeId($request->stack_type_id),
				"charge_type_id" => ChargeTypeId($request->charge_type_id),
				"is_remote_pick" => @$request->is_remote_pick,
				"is_allergen_pick" => @$request->is_allergen_pick,
				"is_customer_called" => @$request->is_customer_called,
			], ['order_id' => $order->id]));
			
			DB::commit();

			return json_response(200, __("Tenant.order_save_success"));	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.order_fail"));
		}
	}
} 