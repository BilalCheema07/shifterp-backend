<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use Exception;
use App\Models\Tenant\{Order};
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmartSchedule\UpdateOrderRequest;

class UpdateOrderController extends Controller
{
	public function update(UpdateOrderRequest $request)
	{
		switch ($request->type) {
			case "basic_info":
				return $this->basicInfo($request);
				break;
			case "product":
				return $this->product($request);
				break;
			case "shipping":
				return $this->shipping($request);
				break;
		}
	}

	public function basicInfo($request)
	{
		DB::beginTransaction();
		try {
			$order = Order::findByUUID($request->order_id);
			$result = $order->update([
				"date" => dateFormat($request->date),
				"time" => timeFormat($request->time),
				"po_number" => @$request->po_number,
				"release_number" => @$request->release_number,
			]);

			DB::commit();

			return json_response(200, __("Tenant.order_update_success"));	
		} catch (Exception $e) {
			DB::rollBack();

			return json_response(500, __("Tenant.order_update_fail"));
		}
	}

	public function product($request)
	{
		DB::beginTransaction();
		try {
			$order = Order::findByUUID($request->order_id);
			switch ($order->type) {
				case "shipping":
					$result = $order->update([
						"notes" => $request->notes,
					]);
					$result2 = $order->shippingOrder->update([
						"stack_type_id" => $request->stack_type_id ? stackTypeId($request->stack_type_id) : $order->shippingOrder->stack_type_id,
						"charge_type_id" => $request->charge_type_id ? ChargeTypeId($request->charge_type_id) : $order->shippingOrder->charge_type_id,
						"is_remote_pick" => @$request->is_remote_pick ? $request->is_remote_pick : 0,
						"is_allergen_pick" => @$request->is_allergen_pick ? $request->is_allergen_pick : 0,
						"is_customer_called" => @$request->is_customer_called ? $request->is_customer_called : 0,
					]);

					break;
				case "receiving":
					$result = $order->update([
						"notes" => $request->notes,
					]);
					break;
				case "production":
					$result = $order->update([
						"notes" => $request->notes,
					]);
					$result2 = $order->productionOrder->update([
						"is_remote_pick" => @$request->is_remote_pick ? $request->is_remote_pick : 0,
						"is_allergen_pick" => @$request->is_allergen_pick ? $request->is_allergen_pick : 0,
					]);
					break;
				case "blend":
					$result = $order->update([
						"notes" => $request->notes,
					]);
					$result2 = $order->blendOrder->update([
						"is_remote_pick" => @$request->is_remote_pick ? $request->is_remote_pick : 0,
					]);
					break;
			}

			DB::commit();

			return json_response(200, __("Tenant.order_update_success"));	
		} catch (Exception $e) {
			DB::rollBack();

			return json_response(500, __("Tenant.order_update_fail"));
		}
	}

	public function shipping($request)
	{
		DB::beginTransaction();
		try {
			$order = Order::findByUUID($request->order_id);
			$order->drivers()->detach();
			$order->drivers()->attach(DriverId(@$request->driver1_id), ["type"=> 1]);
			$order->drivers()->attach(DriverId(@$request->driver2_id), ["type"=> 2]);

			switch ($order->type) {
				case "shipping":
					$result2 = $order->shippingOrder->update([
						"shipper_id" => ShipperId($request->shipper_id),
						"ship_to_id" => ShipTo($request->ship_to_id),
					]);

					break;
				case "receiving":
					$result2 = $order->receivingOrder->update([
						"shipper_id" => ShipperId($request->shipper_id),
					]);
					break;
				case "production":
					break;
				case "blend":
					break;
			}

			DB::commit();

			return json_response(200, __("Tenant.order_update_success"));	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.order_update_fail"));
		}
	}
}
