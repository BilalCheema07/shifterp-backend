<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmartSchedule\OrdersRequest;
use App\Services\Tenant\SmartSchedule\BlendOrderService;

class BlendOrderController extends Controller
{
	public function addNewBlendOrder(OrdersRequest $request)
	{
		$blend_service = new BlendOrderService;
		return $blend_service->addNewBlendOrder($request);
	}
}
