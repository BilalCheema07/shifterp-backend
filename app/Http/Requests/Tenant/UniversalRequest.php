<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UniversalRequest extends FormRequest
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
			case "customer-products":
				return $this->customerProducts();
			case "customer-kits":
				return $this->customerKits();
			default:
				return $this->universalModuleData();
		}
	}

    public function universalModuleData(){
        return [
			"module_name" => "required",
            "fields" => "required|array",
		];
    }

	protected function customerProducts(){
		return [
			"customer_id" => "required|exists:customers,uuid",
		];
	}
	protected function customerKits(){
		return [
			"customer_id" => "required|exists:customers,uuid",
		];
	}
}