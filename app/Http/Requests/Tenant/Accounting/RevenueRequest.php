<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class RevenueRequest extends FormRequest
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
			case "add-new-revenue":
				return $this->addNewRevenue();
			case "list-revenue":
				return $this->listingRevenue();
			case "get-single-revenue":
				return $this->getSingleRevenue();
			case "update-revenue":
				return $this->updateRevenue();
			default:
				return $this->deleteRevenue();
		}
	}

	protected function addNewRevenue()
	{
		return [
			"revenues.*.revenue_type_id" => "required|string|exists:revenue_types,uuid",
			"revenues.*.shift_id" => "required|exists:shifts,uuid",
			"revenues.*.amount" => "required",
			"revenues.*.date" => "required",
			"revenues.*.notes" => "nullable",
		];
	}

	protected function listingRevenue()
	{
		return [
			"revenue_type_id" => "nullable|array",
			"revenue_type_id.*" => "exists:revenue_types,uuid",
			"date" => "nullable",
			"search" => "nullable|string"
		];
	}

	protected function getSingleRevenue()
	{
		return [
			"revenue_uuid" => "required|exists:revenues,uuid"
		];
	}


	protected function updateRevenue()
	{
		return [
			"uuid" => "required|exists:revenues,uuid",
			"revenue_type_id" => "required|string|exists:revenue_types,uuid",
			"shift_id" => "required|exists:shifts,uuid",
			"amount" => "required",
			"date" => "required",
			"notes" => "nullable",
		];
	}

	protected function deleteRevenue()
	{
		return [
			"revenue_uuid" => "required|array",
			"revenue_uuid.*" => "exists:revenues,uuid"
		];
	}
}
