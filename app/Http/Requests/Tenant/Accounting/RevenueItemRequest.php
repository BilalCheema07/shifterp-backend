<?php

namespace App\Http\Requests\Tenant\Accounting;

use App\Rules\Tenant\RevenueItemRule;
use Illuminate\Foundation\Http\FormRequest;

class RevenueItemRequest extends FormRequest
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
			case "add-new-revenue-item":
				return $this->addNewRevenueItem();
			case "update-revenue-item":
				return $this->updateRevenueItem();
			default:
				return $this->deleteRevenueItem();
		}
	}

	protected function addNewRevenueItem()
	{
		return [
			"name" => "required|string|unique:revenue_items,name"
		];
	}

	protected function updateRevenueItem()
	{
		return [
			"uuid" => "required|string|exists:revenue_items,uuid",
			"name" => "required|string|unique:revenue_items,name,{$this->uuid},uuid",
		];
	}

	protected function deleteRevenueItem()
	{
		return [
			"revenue_item_uuid" => [
				"required",
				"string",
				"exists:revenue_items,uuid",
				new RevenueItemRule()
		],
		];
	}
}
