<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
			"remote_pick" => "nullable|in:0,1",
			"allergen_pick" => "nullable|in:0,1",
			"tall_location" => "nullable|in:0,1",
			"status" => "nullable|in:0,1",
        	"search" => "nullable|string"
		];
	}

    protected function save()
	{
		return [
			"remote_pick" => "required|in:0,1",
			"allergen_pick" => "required|in:0,1",
			"tall_location" => "required|in:0,1",
			"status" => "required|in:0,1",
        	"name" => "required|string|unique:locations,name",
        	"barcode" => "required|string|unique:locations,barcode",
        	"custom_capacity" => "required|integer",
		];
	}
	
    protected function show()
	{
		return [
			"uuid" => "required|exists:locations,uuid"
		];
	}

	protected function update()
	{
		return [
			"uuid" => "required|exists:locations,uuid",
			"remote_pick" => "required|in:0,1",
			"allergen_pick" => "required|in:0,1",
			"tall_location" => "required|in:0,1",
			"status" => "required|in:0,1",
        	"name" => "required|string|unique:locations,name,{$this->uuid},uuid",
        	"barcode" => "required|string|unique:locations,barcode,{$this->uuid},uuid",
        	"custom_capacity" => "required|integer",
		];
	}

	protected function delete(){
		return [
			"ids" => "required|array",
			"ids.*" => "exists:locations,uuid"
		];
	}
}
