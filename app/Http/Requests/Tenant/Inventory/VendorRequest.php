<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
			"vendor_ids" => "nullable|array",
			"vendor_ids.*" => "exists:vendors,uuid",
			"status" => "nullable|in:0,1",
        	"search" => "nullable|string"
		];
	}
    protected function save()
	{
		return [
			"company_name" => "required|string|unique:vendors,company_name",
        	"dba_name" => "required|string",
        	"address" => "required",
			"city" => "required",
			"state" => "required",
        	"zip_code" => "required",
        	"status" => "required",
        	"primary_contact_name" => "required|string",
        	"primary_contact_email" => "required|email",
        	"primary_contact_phone" => "required"
		];
	}
	
    protected function show()
	{
		return [
			"uuid" => "required|exists:vendors,uuid"
		];
	}

	protected function update()
	{
			return [
				"uuid" => "required|exists:vendors,uuid",
				"company_name" => "required|string|unique:vendors,company_name,{$this->uuid},uuid",
				"dba_name" => "required|string",
				"address" => "required",
				"city" => "required",
				"state" => "required",
				"zip_code" => "required",
				"status" => "required",
				"primary_contact_name" => "required|string",
				"primary_contact_email" => "required|email",
				"primary_contact_phone" => "required"
			];
	}
	
	protected function delete(){
		return [
			"id" => "required|exists:vendors,uuid",
		];
	}
}
