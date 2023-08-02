<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmartSchedule\OrdersRequest;
use App\Services\Tenant\SmartSchedule\ProductionOrderService;

class ProductionOrderController extends Controller
{
	public function addNewProductionOrder(OrdersRequest $request)
	{
		$prod_service = new ProductionOrderService;
		return $prod_service->addNewProductionOrder($request);
	}
}
