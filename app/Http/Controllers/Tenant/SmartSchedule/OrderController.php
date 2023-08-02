<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Imports\ImportOrder;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Tenant\SmartSchedule\OrdersRequest;
use App\Http\Resources\Tenant\SmartSchedule\{OrderCollection, OrderDetailResource};
use App\Models\Tenant\{Note, ChargeType, Customer, Driver, Kit, Order, Shipper, ShipTo, StackType, Unit};

class OrderController extends Controller
{
	public function dependencies()
	{
		$data = [];
		$data["customer"] = Customer::select("uuid","code","name")->get();
		$data["drivers"] = Driver::select("uuid","name")->get();
		$data["units"] = Unit::select("uuid","name")->get();
		$data["kits"] = Kit::select("uuid","name")->get();
		$data["shipper"] = Shipper::select("uuid","shipper_name","shipper_code")->get();
		$data["shipTo"] = ShipTo::select("uuid","name")->get();
		$data["charge_types"] = ChargeType::where('type', 'shipping_order')->select("uuid","name")->get();
		$data["stack_types"] = StackType::select("uuid","name")->get();
		return json_response(200, __("Tenant.dependency_fetch"), $data);

	}

	public function list(OrdersRequest $request)
	{
		$order = Order::where('status', '<>', 'cancel')->with("blendOrder", "productionOrder", "receivingOrder", "shippingOrder", "customer", "connectedChildOrders:id,parent_order_id");

		$order = isset($request->status) ? $order->where("status", $request->status) : $order;
		$order = isset($request->order_type) ? $order->where("type", $request->order_type) : $order;
		$date = isset($request->date) ? date("Y-m-d", strtotime($request->date)) : date("Y-m-d");
		$duration = isset($request->duration) ? $request->duration : "";
		$order = $order->GetDurationRecord($date,$duration);
		$order = $order->get();

		$data['orders'] = [];
		if(count($order) > 0 ){
			$data['orders'] = new OrderCollection($order);
		}
		$data['notes'] = Note::all();
		return json_response(200, __("Tenant.order_listed"), $data);
	}

	public function possibleConnectedOrders(OrdersRequest $request)
	{
		$customer = Customer::findByUUID($request->customer_id);
		$orders = Order::with("blendOrder", "productionOrder", "receivingOrder", "shippingOrder", "customer")->where("customer_id", $customer->id);
		switch ($request->type) {
			case "blend":
				$orders = $orders->whereIn("type", ["production", "shipping"])->get();
				return json_response(200, __("Tenant.order_listed"), ["orders" => new OrderCollection($orders)]);
			case "production":
				$orders = $orders->whereIn("type", ["shipping"])->get();
				return json_response(200, __("Tenant.order_listed"), ["orders" => new OrderCollection($orders)]);
			case "receiving":
				$orders = $orders->whereIn("type", ["production", "blend"])->get();
				return json_response(200, __("Tenant.order_listed"), ["orders" => new OrderCollection($orders)]);
			case "shipping":
				return json_response(200, __("Tenant.order_listed"), ["orders" => []]);
		}
		return json_response(200, __("Tenant.order_listed"), ["orders" => []]);
	}

	public function connectedOrders(OrdersRequest $request)
	{
		$order = Order::findByUUID($request->order_id);
		$con_ids = [];
		if (count($order->connectedChildOrders)) {
			$con_ids = $this->getConnectedIds($order->connectedChildOrders, $con_ids);
		}
		$connected_orders = Order::whereIn("id", $con_ids)
			->with("blendOrder", "productionOrder", "receivingOrder", "shippingOrder", "customer", "connectedParentOrder:id,uuid")
			->get();
		return  json_response(200, __("Tenant.order_listed"), [
			"connected_orders" => $connected_orders
		]);
	}

	private function getConnectedIds($conOrders, $con_ids = [])
	{
		foreach ($conOrders as $order) {
			$con_ids[] = $order->id;
			if(count($order->connectedChildOrders)) {
				$con_ids = $this->getConnectedIds($order->connectedChildOrders, $con_ids);
			}
		}
		return $con_ids;
	}
	
	public function csvFileUpload(OrdersRequest $request)
	{
		Excel::queueImport(new ImportOrder, $request->file('file')->store('files'));
		return json_response(200, 'Import successfully added.');
	 }
 
	//  public function exportUsers(Request $request)
	//  {
	// 	return Excel::download(new ExportOrder, 'users.xlsx');
	//  }

	//Single Order Detail
	public function orderDetail(OrdersRequest $request)
	{
		$order = Order::findByUUIDOrFail($request->order_uuid);
		return json_response(200, __("Tenant.order_detail"), ["orders" => new OrderDetailResource($order)]);
	}

	public function cancelOrder(OrdersRequest $request)
	{
		$order = Order::findByUUIDOrFail($request->order_uuid);
		abort_if($order->status === 'cancel', 403, __("Tenant.order_status_fail"));

		$order->status = 'cancel';
		$order->save();
		return json_response(200, __("Tenant.order_status_success"));
	}

}
