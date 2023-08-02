<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ShipToRequest extends FormRequest
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
			case "save":
				return $this->save();
			case "get":
				return $this->show();
			case "update":
				return $this->update();
			case "delete":
				return $this->delete();
			case "multi-status-update":
				return $this->multiOption();
			default:
				return $this->list();
		}
	}

	protected function list()
	{
		return [
			
			"shipto_ids" => "nullable|array",
			"shipto_ids.*" => "exists:ship_tos,uuid",
			"external_id" => "nullable|integer",
			"status" => "nullable|in:0,1",
        	"search" => "nullable|string"
		];
	}
	
    protected function save()
	{
		return [
			"customer_id" 			=> "required|exists:customers,uuid",
			"name" 					=> "required|string|unique:ship_tos,name",
			"external_id"  			=> "required|integer|unique:ship_tos,external_id",
			"address1"  			=> "required|string",
			"address2"  			=> "required|string",
			"city" 					=> "required",
			"state" 				=> "required",
        	"zip_code" 				=> "required",
        	"status" 				=> "required",
        	"primary_contact_name" 	=> "required|string",
        	"primary_contact_email" => "required|email",
        	"primary_contact_phone" => "required"
		];
	}
	
    protected function show()
	{
		return [
			"uuid" => "required|exists:ship_tos,uuid"
		];
	}

	protected function update()
	{
			return [
				"uuid" 					=> "required|exists:ship_tos,uuid",
				"customer_id" 			=> "required|exists:customers,uuid",
				"name" 					=> "required|string|unique:ship_tos,name,{$this->uuid},uuid",
				"external_id"  			=> "required|integer|unique:ship_tos,external_id,{$this->uuid},uuid",
				"address1"  			=> "required|string",
				"address2"  			=> "required|string",
				"city" 					=> "required",
				"state" 				=> "required",
				"zip_code" 				=> "required",
				"status" 				=> "required",
				"primary_contact_name" 	=> "required|string",
				"primary_contact_email" => "required|email",
				"primary_contact_phone" => "required"
			];
	}


	protected function delete(){
		return [
			"id" => "required|exists:ship_tos,uuid",
			"ship_to_reassign" => "required|exists:ship_tos,uuid"
		];
	}
	protected function multiOption(){
		return [
		"shipto_ids" => "required|array",
		"shipto_ids.*" => "exists:ship_tos,uuid",
		"action" => "required",
		"customer_id" => "nullable",
		];
	}
}
