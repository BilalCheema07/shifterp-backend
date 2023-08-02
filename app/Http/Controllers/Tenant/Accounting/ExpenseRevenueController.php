<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ExpenseRevenue;
use App\Http\Requests\Tenant\Accounting\ExpenseRevenueRequest;
use App\Http\Resources\Tenant\Accounting\ExpenseRevenueCollection;
use App\Services\Tenant\Accounting\ExpenseRevenueService;

class ExpenseRevenueController extends Controller
{
	protected $service;

	function __construct(ExpenseRevenueService $service)
	{
		$this->service = $service;
	}

	public function listExpenseRevenue(ExpenseRevenueRequest $request) 
	{
		$expense_revenue = new ExpenseRevenue;

		$expense_revenue = $request->revenue_type_id ? $expense_revenue->revenueTypeString($request->revenue_type_id) : $expense_revenue;
		$expense_revenue = $request->revenue_item_id ? $expense_revenue->revenueItemString($request->revenue_item_id) : $expense_revenue;
		$expense_revenue = $request->facility_id ? $expense_revenue->facilityString($request->facility_id) : $expense_revenue;
		$expense_revenue = $request->date ? $expense_revenue->date(dateFormat($request->date)) : $expense_revenue;
		$expense_revenue = $request->search ? $expense_revenue->whereSearch($request->search) : $expense_revenue;

		return json_response(200, __("Tenant.expense_revenue_get_list"), ["revenues" => new ExpenseRevenueCollection($expense_revenue->get())]);
	}

	public function addNewExpenseRevenue(ExpenseRevenueRequest $request) 
	{
		return $this->service->store($request);
	}

	public function updateExpenseRevenue(ExpenseRevenueRequest $request) 
	{
		return $this->service->update($request);
	}

	public function deleteExpenseRevenue(ExpenseRevenueRequest $request) 
	{
		if(auth()->user()->role !== 'company_admin') {
            return json_response(401,__('auth.auth_error'));
        }
		$expense_revenues = ExpenseRevenue::getByUUID($request->expense_revenue_uuid);
		foreach ($expense_revenues as $expense_revenue) {
			$expense_revenue->delete();
		}
		return json_response(200, __("Tenant.delete_expense_revenue"));
	}
}
