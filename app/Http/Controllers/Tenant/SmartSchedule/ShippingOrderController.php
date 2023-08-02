<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Tenant\SmartSchedule\OrdersRequest;
use App\Services\Tenant\SmartSchedule\ShippingOrderService;

class ShippingOrderController extends Controller
{
    public function addNewShippingOrder(OrdersRequest $request)
	{
		$prod_service = new ShippingOrderService;
		return $prod_service->addNewShippingOrder($request);
	}
}
