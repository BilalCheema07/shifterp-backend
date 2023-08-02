<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRevenueRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		switch (last(request()->segments())) {
			case "add-new-expense-revenue":
				return $this->addNewExpenseRevenue();
			case "list-expense-revenue":
				return $this->listExpenseRevenue();
			case "update-expense-revenue":
				return $this->updateExpenseRevenue();
			default:
				return $this->deleteExpenseRevenue();
		}
	}

	protected function addNewExpenseRevenue()
	{
		return [
			"date" => "required",
			"customer_id" => "required|string|exists:customers,uuid",
			"facility_id" => "required|string|exists:facilities,uuid",
			"revenues.*.revenue_type_id" => "required|string|exists:revenue_types,uuid",
			"revenues.*.revenue_item_id" => "required|string|exists:revenue_items,uuid",
			"revenues.*.shift_id" => "nullable|exists:shifts,uuid",
			"revenues.*.amount" => "required",
			"revenues.*.notes" => "nullable",
		];
	}

	protected function listExpenseRevenue()
	{
		return [
			"revenue_type_id" => "nullable|array",
			"revenue_type_id.*" => "exists:revenue_types,uuid",
			"revenue_item_id" => "nullable|array",
			"revenue_item_id.*" => "exists:revenue_items,uuid",
			"facility_id" => "nullable|array",
			"facility_id.*" => "exists:facilities,uuid",
			"date" => "nullable",
			"search" => "nullable|string"
		];
	}


	protected function updateExpenseRevenue()
	{
		return [
			"uuid" => "required|exists:expense_revenues,uuid",
			"date" => "required",
			"customer_id" => "required|string|exists:customers,uuid",
			"facility_id" => "required|string|exists:facilities,uuid",
			"revenue_type_id" => "required|string|exists:revenue_types,uuid",
			"revenue_item_id" => "required|string|exists:revenue_items,uuid",
			"shift_id" => "nullable|exists:shifts,uuid",
			"amount" => "required",
			"notes" => "nullable",
		];
	}

	protected function deleteExpenseRevenue()
	{
		return [
			"expense_revenue_uuid" => "required|array",
			"expense_revenue_uuid.*" => "exists:expense_revenues,uuid"
		];
	}
}
