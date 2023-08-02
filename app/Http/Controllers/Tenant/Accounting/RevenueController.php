<?php

namespace App\Http\Controllers\Tenant\Accounting;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\RevenueRequest;
use App\Http\Resources\Tenant\Accounting\RevenueCollection;
use App\Models\Tenant\Revenue;
use App\Models\Tenant\RevenueType;
use App\Models\Tenant\Shift;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
	public function revenueList(RevenueRequest $request)
	{
		$revenue = new Revenue;
		$revenue = $request->revenue_type_id ? $revenue->revenueTypeString($request->revenue_type_id) : $revenue;
		$revenue = $request->date ? $revenue->date(dateFormat($request->date)) : $revenue;
		$revenue = $request->search ? $revenue->whereSearch($request->search) : $revenue;

		return json_response(200, __("Tenant.revenue_get_list"), ["revenues" => new RevenueCollection($revenue->get())]);
	}

	public function addNewRevenue(RevenueRequest $request)
	{
		DB::beginTransaction();
		try {
			foreach($request->revenues as $revenue) {
				$revenue = (object)$revenue;
	
				$revenue_type = RevenueType::findByUUIDOrFail($revenue->revenue_type_id)->id;
				$shift = Shift::findByUUIDOrFail($revenue->shift_id)->id;
			Revenue::create([
				'revenue_type_id' => $revenue_type,
				'shift_id' => $shift,
				'amount' => $revenue->amount,
				'date' => dateFormat($revenue->date),
				'notes' => @$revenue->notes ?? '',
			]);
		}
			DB::commit();
			return json_response(200, __("Tenant.add_new_revenue_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.add_new_revenue_fail'));
		}
	}
	
	public function getSingleRevenue(RevenueRequest $request)
	{
		return json_response(
			200,
			__("Tenant.single_revenue"),
			(Revenue::findByUUIDOrFail($request->revenue_uuid))
		);
	}
	
	public function deleteRevenue(RevenueRequest $request)
	{
		$revenues = Revenue::getByUUID($request->revenue_uuid);
		foreach ($revenues as $revenue) {
			$revenue->delete();
		}
		return json_response(200, __("Tenant.delete_revenue"));
	}

	public function updateRevenue(RevenueRequest $request)
	{
		$revenue = Revenue::findByUUIDOrFail($request->uuid);
		$shift = Shift::findByUUIDOrFail($request->shift_id)->id;
		$revenue_type = RevenueType::findByUUIDOrFail($request->revenue_type_id)->id;

		DB::beginTransaction();
		try {
			$revenue->update(array_merge($request->all(), [
				'revenue_type_id' => $revenue_type,
				'shift_id' => $shift,
				'date' => dateFormat($request->date),
			]));	
			DB::commit();
			return json_response(200, __("Tenant.update_revenue_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.update_revenue_fail'));
		}
	}
}
