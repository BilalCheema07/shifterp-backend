<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
			case "add-new-expense":
				return $this->addNewExpense();
			case "list-expense":
				return $this->listingExpense();
			case "get-single-expense":
				return $this->getSingleExpense();
			case "update-expense":
				return $this->updateExpense();
			default:
				return $this->deleteExpense();
		}
	}

	protected function addNewExpense()
	{
		return [
			"expense_type_id" => "required|string|exists:expense_types,uuid",
			"date" => "required",
			"data.*.type_id" => "required|string|exists:expense_types,uuid",
			"data.*.amount" => "required",
		];
	}

	protected function listingExpense()
	{
		return [
			"expense_type_id" => "nullable|array",
			"expense_type_id.*" => "exists:expense_types,uuid" ,
			"date"  => "nullable",
			"search" => "nullable|string"
		];
	}

	protected function getSingleExpense()
	{
		return [
			"expense_uuid" => "required|array",
		];
	}

	protected function updateExpense()
	{
		return [
			"uuid" => "required|string|exists:expenses,uuid",
			"expense_type_id" => "required|string|exists:expense_types,uuid",
			"date" => "required",
			"data.*.type_id" => "required|string|exists:expense_types,uuid",
			"data.*.amount" => "required",
		];
	}

	protected function deleteExpense()
	{
		return [
			"expense_uuid" => "required|array",
            "expense_uuid.*" => "exists:expenses,uuid"
		];
	}
}
