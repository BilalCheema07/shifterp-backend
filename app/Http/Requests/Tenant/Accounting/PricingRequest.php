<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class PricingRequest extends FormRequest
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
			case "add-new-pricing":
				return $this->addNewPricing();
			case "get-single-pricing":
				return $this->getSinglePricing();
			case "list-pricing":
				return $this->listingPricing();
			case "update-pricing":
				return $this->updatePricing();
			default:
				return $this->deletePricing();
		}
	}

	protected function addNewPricing()
	{
		return [
			"name" => "required|string|unique:pricings,name",
			"customer_id" => "required|exists:customers,uuid",
			"category_id" => "required|exists:categories,uuid",
			"product_id" => "required|exists:products,uuid",
			"pricing_type_id" => "required|exists:pricing_types,uuid",
			"charge_type_id" => "required|exists:charge_types,uuid",
			"unit_id" => "required|exists:units,uuid",
			"lot_number" => "nullable|integer",
			"lod_id1" => "nullable|integer",
			"lod_id2" => "nullable|integer",
			"grace_period" => "nullable|integer",
			"price_per_unit" => "nullable|integer",
			"min_charge" => "nullable|integer",
			"status" => "nullable|in:0,1",
		];
	}

	protected function getSinglePricing()
	{
		return [
			"pricing_uuid" => "required|exists:pricings,uuid"
		];
	}

	protected function deletePricing()
	{
		return [
			"pricing_uuid" => "required|exists:pricings,uuid",
			"pricing_reassign_uuid" => "required|exists:pricings,uuid"
		];
	}

	protected function listingPricing()
	{
		return [
			"status" => "nullable|in:0,1",
			"price_type_uuid" => "nullable|array",
			"price_type_uuid.*" => "exists:pricing_types,uuid" 
		];
	}

	protected function updatePricing ()
	{
		return [
			"uuid" => "required|exists:pricings,uuid",
			"name" => "required|string|unique:pricings,name,{$this->uuid},uuid",
			"customer_id" => "required|exists:customers,uuid",
			"category_id" => "required|exists:categories,uuid",
			"product_id" => "required|exists:products,uuid",
			"pricing_type_id" => "required|exists:pricing_types,uuid",
			"charge_type_id" => "required|exists:charge_types,uuid",
			"unit_id" => "required|exists:units,uuid",
			"lot_number" => "nullable|integer",
			"lod_id1" => "nullable|integer",
			"lod_id2" => "nullable|integer",
			"grace_period" => "required|integer",
			"price_per_unit" => "required|numeric",
			"min_charge" => "nullable|numeric",
			"status" => "nullable|in:0,1",
		];
	}
}
