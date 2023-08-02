<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\SmartSchedule\OrdersRequest;
use App\Services\Tenant\SmartSchedule\ReceivingOrderService;


class ReceivingOrderController extends Controller
{
    
	public function addNewReceivingOrder(OrdersRequest $request)
	{
		$prod_service = new ReceivingOrderService;
		return $prod_service->addNewReceivingOrder($request);
	}
}
