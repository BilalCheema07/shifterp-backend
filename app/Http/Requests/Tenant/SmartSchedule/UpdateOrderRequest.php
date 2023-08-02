<?php

namespace App\Http\Requests\Tenant\SmartSchedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
	 * @return array
	 */
	public function rules()
	{
		switch ($this->type) {
			case "basic_info":
				return $this->basicInfo();
			case "product":
				return $this->product();
			case "shipping":
				return $this->shipping();
			default: 
				return [
					"type" => "required|in:basic_info,product,shipping",
				];
		}
	}

	public function basicInfo()
	{
		return [
			"order_id" => "required|exists:orders,uuid",
			"date" => "required",
			"time" => "required",
			"po_number" => "nullable",
			"release_number" => "nullable",
		];
	}
	
	public function shipping()
	{
		return [
			"order_id" => "required|exists:orders,uuid",
			"shipper_id" => "nullable|exists:shippers,uuid",
			"ship_to_id" => "nullable|exists:ship_tos,uuid",
			"driver1_id" => "required|exists:drivers,uuid",
			"driver2_id" => "required|exists:drivers,uuid",
		];
	}
	
	public function product()
	{
		return [
			"order_id" => "required|exists:orders,uuid",
			"stack_type_id" => "nullable|exists:stack_types,uuid",
			"charge_type_id" => "nullable|exists:charge_types,uuid",
			"is_remote_pick" => "nullable|in:0,1",
			"is_allergen_pick" => "nullable|in:0,1",
			"is_customer_called" => "nullable|in:0,1",
			"notes" => "nullable|string",
		];
	}
}
