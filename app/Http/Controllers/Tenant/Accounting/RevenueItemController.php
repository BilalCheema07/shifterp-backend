<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Models\Tenant\RevenueItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\RevenueItemRequest;

class RevenueItemController extends Controller
{	
	public function listRevenueItem()
	{	
		return json_response(
			200,
			__("Tenant.revenue_item_get_list"),
			RevenueItem::select("uuid", "name")->get()
		);
	}
	
	public function addNewRevenueItem(RevenueItemRequest $request)
	{
		RevenueItem::create($request->all());
		return json_response(200, __("Tenant.add_new_revenue_item_success"));
	}

	public function updateRevenueItem(RevenueItemRequest $request)
	{
		$revenue_item = RevenueItem::findByUUIDOrFail($request->uuid);
		$revenue_item->update($request->all());
		return json_response(200, __("Tenant.update_revenue_item_success"));
	}

	public function deleteRevenueItem(RevenueItemRequest $request)
	{
		return json_response(
			200,
			__("Tenant.delete_revenue_item"),
			RevenueItem::findByUUIDOrFail($request->revenue_item_uuid)->delete()
		);
	}
}