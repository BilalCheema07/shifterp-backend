<?php 
namespace App\Services\Tenant\Accounting;

use App\Models\Tenant\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\ExpenseRevenue;
use App\Models\Tenant\Facility;

class ExpenseRevenueService 
{
	public function store($request)
	{
		if(auth()->user()->role !== 'company_admin') {
			return json_response(401,__('auth.auth_error'));
		} 
		DB::beginTransaction();
		try {
			foreach($request->revenues as $revenue) {
				$revenue = (object)$revenue;
				ExpenseRevenue::create([
					'customer_id' => customerId($request->customer_id),
					'facility_id' => facilityId($request->facility_id),
					'revenue_type_id' => revenueTypeId($revenue->revenue_type_id),
					'shift_id' => @$revenue->shift_id ? shiftId(@$revenue->shift_id) : NULL,
					'revenue_item_id' => revenueItemId($revenue->revenue_item_id),
					'amount' => $revenue->amount,
					'date' => dateFormat($request->date),
					'notes' => @$revenue->notes ?? '',
				]);
			}
			DB::commit();
			return json_response(200, __("Tenant.add_new_expense_revenue_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.add_new_expense_revenue_fail'));
		}
	}

	public function update($request)
	{
		if(auth()->user()->role !== 'company_admin') {
			return json_response(401,__('auth.auth_error'));
		} 
		DB::beginTransaction();
		try {
			$expense_revenue = ExpenseRevenue::findByUUIDOrFail($request->uuid);
			$expense_revenue->update([
					'customer_id' => customerId($request->customer_id),
					'facility_id' => facilityId($request->facility_id),
					'revenue_type_id' => revenueTypeId($request->revenue_type_id),
					'shift_id' => @$request->shift_id ? shiftId(@$request->shift_id) : NULL,
					'revenue_item_id' => revenueItemId($request->revenue_item_id),
					'amount' => $request->amount,
					'date' => dateFormat($request->date),
					'notes' => @$request->notes ?? '',
				]);

			DB::commit();
			return json_response(200, __("Tenant.update_expense_revenue_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.update_expense_revenue_fail'));
		}
	} 
}
