<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Tenant\CustomerDeleteRule;

class CustomerRequest extends FormRequest
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
		switch (last(request()->segments())) {
			case "show":
				return $this->uuidCheck();
			case "list":
				return $this->filters();
			case "save":
				return $this->save();
			case "update":
				return $this->update();
			case "delete":
				return $this->delete();
			default:
				return $this->customer_code();
		}
	}



	protected function uuidCheck()
	{
		return [
			"uuid" => "required|exists:customers,uuid"
		];
	}

	protected function filters()
	{
		return [
			"search" => "nullable|string",
			"status" => "nullable|in:0,1",
			"order" => "nullable|in:asc,desc"
		];
	}

	protected function save()
	{
		return [
			"name" => "required",
			"code" => "required|unique:customers,code",
			"production_pick_logic" => "nullable",
			"shipping_pick_logic" => "nullable",
			"min_charge" => "nullable|integer",
			"city" => "nullable",
			"state" => "nullable",
			"zip_code" => "nullable",
			
			"lot_number" => "required|in:0,1",
			"lot_id1" => "required|in:0,1",
			"lot_id2" => "required|in:0,1",
			"receive_date" => "required|in:0,1",
			"production_date" => "required|in:0,1",
			"expiration_date" => "required|in:0,1",
			"show_unit_of_count" => "required|in:0,1",
			"group_by_item" => "required|in:0,1",
			"group_by_lot_number" => "required|in:0,1",
			"group_by_lot_id1" => "required|in:0,1",
			"group_by_lot_id2" => "required|in:0,1",
			"status" => "required|in:0,1",

			"primary_contact_name" => "required",
			"primary_contact_email" => "required|email",
			"primary_contact_phone" => "required",
			'facility_ids' => 'required|array',
			'facility_ids.*' => 'exists:facilities,uuid',
		];
	}

	protected function update()
	{
		return [
			"uuid" => "required|exists:customers,uuid",
			"name" => "required",
			"code" => "required|unique:customers,code,{$this->uuid},uuid",
			"production_pick_logic" => "nullable ",
			"shipping_pick_logic" => "nullable",
			"min_charge" => "nullable|integer",
			"city" => "nullable",
			"state" => "nullable",
			"zip_code" => "nullable",
			
			"lot_number" => "required|in:0,1",
			"lot_id1" => "required|in:0,1",
			"lot_id2" => "required|in:0,1",
			"receive_date" => "required|in:0,1",
			"production_date" => "required|in:0,1",
			"expiration_date" => "required|in:0,1",
			"show_unit_of_count" => "required|in:0,1",
			"group_by_item" => "required|in:0,1",
			"group_by_lot_number" => "required|in:0,1",
			"group_by_lot_id1" => "required|in:0,1",
			"group_by_lot_id2" => "required|in:0,1",
			"status" => "required|in:0,1",

			"primary_contact_name" => "required",
			"primary_contact_email" => "required|email",
			"primary_contact_phone" => "required",
			'facility_ids' => 'required|array',
			'facility_ids.*' => 'exists:facilities,uuid',
		];
	}

	protected function delete()
	{
		return [
			"uuid" => [
				"required",
			 	"exists:customers,uuid",
				new CustomerDeleteRule(),
			],
			"code" => "required|exists:customers,code,uuid,{$this->uuid}",
		];
	}

	protected function customer_code()
	{
		return [
			"code" => "required|exists:customers,code",
		];
	}
}
