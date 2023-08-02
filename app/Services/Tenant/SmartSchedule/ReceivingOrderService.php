<?php 
namespace App\Services\Tenant\SmartSchedule;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{Order, ReceivingOrder};

class ReceivingOrderService
{
	public function addNewReceivingOrder($request)
	{	
		DB::beginTransaction();
		try {
			$connected_order_id = 0;
			if ($request->connected_order_id) {
				$connected_order = Order::findByUUID($request->connected_order_id);
				
				if($connected_order) {
					if (customerId($request->customer_id) !== $connected_order->customer->id || $connected_order->type == "shipping" || $connected_order->type == "receiving") {
						return json_response(403, __("Tenant.invalid_connected_order"));
					}
					$connected_order_id = $connected_order->id;
				}
			}
			$order = Order::createOrder($request->all(), $type = "receiving", $connected_order_id);

			ReceivingOrder::create(array_merge([
				"shipper_id" =>  ShipperId($request->shipper_id),
				"quantity" => $request->quantity,
				"unit_id" => unitId($request->unit_id),
				"receive_form" => customerId($request->received_from),
			], ["order_id" => $order->id]));
			
			DB::commit();

			return json_response(200, __("Tenant.order_save_success"));	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.order_fail"));
		}
	}
} 