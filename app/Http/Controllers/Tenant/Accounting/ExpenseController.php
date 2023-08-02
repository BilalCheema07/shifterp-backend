<?php

namespace App\Http\Controllers\Tenant\Accounting;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\ExpenseRequest;
use App\Http\Resources\Tenant\Accounting\ExpenseCollection;
use App\Models\Tenant\{Expense, ExpenseType};

class ExpenseController extends Controller
{
	public function addNewExpense(ExpenseRequest $request)
	{
		$expense = Expense::where('date', dateFormat($request->date))->first();
		if ($expense) {
			$expense->delete();
		}
		DB::beginTransaction();
		try {
			$expense_type = ExpenseType::whereUUID($request->expense_type_id)->where('parent_id', 0)->firstOrFail();
			$expense = new Expense();
			$expense->date = dateFormat($request->date);
			$expense->expense_type_id = $expense_type->id;
			foreach ($request->data as $value) {

				$uuid = $value['type_id'];

				$results = DB::select( "SELECT * FROM expense_types WHERE parent_id = ' $expense_type->id' AND uuid = '$uuid' ");
				if($results){
					$arr[] = ['type_id' => $results[0]->uuid, 'name' =>  $results[0]->name, 'amount' => $value['amount']];            
				}else{
					$error_array[] = ['Not a type of that specific Expense Type' => $uuid];
				}
			}
			
			if(!empty($error_array) && count($error_array) > 0 ){
				return json_response(500, __('Tenant.add_new_expense_fail'), $error_array);
			}
			$expense->data = json_encode($arr);
			$expense->save();

			DB::commit();
			return json_response(200, __("Tenant.add_new_expense_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.add_new_expense_fail'));
		}
	}

	public function updateExpense(ExpenseRequest $request)
	{
		DB::beginTransaction();
		try {
			$check_expense = Expense::where('date', dateFormat($request->date))->where('uuid', '<>', $request->uuid)->first();
			if ($check_expense) {
					$check_expense->delete();
				}
			$expense = Expense::findByUUIDOrFail($request->uuid);
			$expense_type = ExpenseType::whereUUID($request->expense_type_id)->where('parent_id', 0)->firstOrFail();
			$expense->date = dateFormat($request->date);
			$expense->expense_type_id = $expense_type->id;
			foreach ($request->data as $value) {

				$uuid = $value['type_id'];

				$results = DB::select( "SELECT * FROM expense_types WHERE parent_id = ' $expense_type->id' AND uuid = '$uuid' ");
				if($results){
					$arr[] = ['type_id' => $results[0]->uuid, 'name' =>  $results[0]->name, 'amount' => $value['amount']];            
				}else{
					$error_array[] = ['Not a type of that specific Expense Type' => $uuid];
				}
			}
			
			if(!empty($error_array) && count($error_array) > 0 ){
				return json_response(500, __('Tenant.add_new_expense_fail'), $error_array);
			}
			$expense->data = json_encode($arr);
			$expense->update();

			DB::commit();
			return json_response(200, __("Tenant.update_expense_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.update_expense_fail'));
		}
	}

	public function deleteExpense(ExpenseRequest $request)
	{
		$expenses = Expense::getByUUID($request->expense_uuid);
		foreach ($expenses as $expense) {
			$expense->delete();
		}
		return json_response(200, __("Tenant.delete_expense"));
	}

	public function getSingleExpense(ExpenseRequest $request)
	{
		return json_response(
			200,
			__("Tenant.single_revenue"),
			(Expense::findByUUIDOrFail($request->expense_uuid))
		);
	}

	public function expenseList(ExpenseRequest $request)
	{
		$expense = new Expense;

		$expense = $request->expense_type_id ? $expense->expenseTypeString($request->expense_type_id) : $expense;
		$expense = $request->date ? $expense->date(dateFormat($request->date)) : $expense;
		$expense = $request->search ? $expense->whereSearch($request->search) : $expense;
		
		return json_response(200, __("Tenant.revenue_get_list"), ["expenses" => new ExpenseCollection($expense->get())]);
	}
}