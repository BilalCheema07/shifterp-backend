<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ShipperRequest extends FormRequest
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
			default:
				return $this->list();
		}
	}

	protected function list()
	{
		return [
			
			"shipper_ids" => "nullable|array",
			"shipper_ids.*" => "exists:shippers,uuid",
			"external_id" => "nullable|integer",
			"status" => "nullable|in:0,1",
        	"search" => "nullable|string"
		];
	}

    protected function save()
	{
		return [
			"shipper_name" => "required|string",
			"shipper_code" => "required|string|unique:shippers,shipper_code",
			"city" => "required",
			"state" => "required",
        	"zip_code" => "required",
        	"external_id" => "required|integer|unique:shippers,external_id",
        	"address" => "required",
        	"status" => "required",
        	"primary_contact_name" => "required|string",
        	"primary_contact_email" => "required|email",
        	"primary_contact_phone" => "required"
		];
	}
	
    protected function show()
	{
		return [
			"uuid" => "required|exists:shippers,uuid"
		];
	}

	protected function update()
	{
			return [
				"uuid" => "required|exists:shippers,uuid",
				"shipper_name" => "required|string",
				"shipper_code" => "required|string|unique:shippers,shipper_code,{$this->uuid},uuid",
				"city" => "required",
				"state" => "required",
				"zip_code" => "required",
				"external_id" => "required|integer|unique:shippers,external_id,{$this->uuid},uuid",
				"address" => "required",
				"status" => "required",
				"primary_contact_name" => "required|string",
				"primary_contact_email" => "required|email",
				"primary_contact_phone" => "required"
			];
	}

	protected function delete(){
		return [
			"id" => "required|exists:shippers,uuid",
			"shipper_reassign" => "required|exists:shippers,uuid"
		];
	}
}
