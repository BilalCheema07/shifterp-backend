<?php

namespace App\Http\Requests\Tenant\Inventory;

use App\Rules\Tenant\ProductDeleteRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
			case "list":
				return $this->list();
			case "get":
				return $this->single();
			case "save":
				return $this->save();
			case "update":
				return $this->update();
			default:
				return $this->multiDelete();
		}
	}


	protected function list()
	{
		return [
			"search" => "nullable|string",
			"customer_id" => "nullable|array",
			"customer_id.*" => "exists:customers,uuid",
			"category_id" => "nullable|array",
			"category_id.*" => "exists:categories,uuid",
			"allegen_id" => "nullable|exists:allergens,uuid",
			"high_risk" => "nullable|in:0,1",
			"costed" => "nullable|in:0,1",
			"status" => "nullable|in:0,1",
			"order" => "nullable|in:asc,desc"
		];
	}

	protected function single()
	{
		return [
			"uuid" => "required|exists:products,uuid"
		];
	}

	protected function save()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"category_id" => "required|exists:categories,uuid",
			"name" => "required|string|unique:products,name",
			"description" => "required|string",
			"internal_name" => "nullable|string",
			"internal_description" => "nullable|string",
			"barcode" => "required|string",
			"universal_product_code" => "nullable|string",//upc
			"status" => "required|in:0,1",

			"unit_of_stock" => "required|exists:units,uuid",
			"unit_of_order" => "required|exists:units,uuid",
			"unit_of_count" => "required|exists:units,uuid",
			"unit_of_package" => "required|exists:units,uuid",
			"unit_of_sell" => "required|exists:units,uuid",
			"unit_of_assembly" => "nullable|exists:units,uuid",
			"unit_of_purchase" => "nullable|exists:units,uuid",
			"variable_unit1" => "nullable|exists:units,uuid",
			"variable_unit2" => "nullable|exists:units,uuid",
			"convert_to_unit1" => "required|exists:units,uuid",
			"convert_to_unit2" => "required|exists:units,uuid",
			"convert_to_unit3" => "nullable|exists:units,uuid",
			"unit1_multiplier" => "required|numeric",
			"unit2_multiplier" => "required|numeric",
			"unit3_multiplier" => "required|numeric",
			"item_gross_weight" => "required|numeric",
			
			"pallet_tie" => "nullable|integer",
			"kit_parent_cost" => "nullable|numeric",
			"shelve_life" => "nullable|integer",
			"safety_stock" => "nullable|numeric",
			"safety_stock_unit" => "nullable|exists:units,uuid",
			"par_level" => "nullable",
			"par_level_unit" => "nullable|exists:units,uuid",
			"minimum_blend_amount" => "nullable|integer",
			"is_global" => "nullable|in:0,1",
			"is_kit_parent" => "nullable|in:0,1",
			"is_high_risk" => "nullable|in:0,1",
			"cost_item" => "nullable|in:0,1",

			"allergen_ids" => "nullable|array",
			"allergen_ids.*" => "exists:allergens,uuid"
		];
	}

	protected function update()
	{
		return [
			"uuid" => "required|exists:products,uuid",
			"customer_id" => "required|exists:customers,uuid",
			"category_id" => "required|exists:categories,uuid",
			"name" => "required|string|unique:products,name,{$this->uuid},uuid",
			"description" => "required|string",
			"internal_name" => "nullable|string",
			"internal_description" => "nullable|string",
			"barcode" => "required|string",
			"universal_product_code" => "nullable|string",//upc
			"status" => "required|in:0,1",

			"unit_of_stock" => "required|exists:units,uuid",
			"unit_of_order" => "required|exists:units,uuid",
			"unit_of_count" => "required|exists:units,uuid",
			"unit_of_package" => "required|exists:units,uuid",
			"unit_of_sell" => "required|exists:units,uuid",
			"unit_of_assembly" => "nullable|exists:units,uuid",
			"unit_of_purchase" => "nullable|exists:units,uuid",
			"variable_unit1" => "nullable|exists:units,uuid",
			"variable_unit2" => "nullable|exists:units,uuid",
			"convert_to_unit1" => "required|exists:units,uuid",
			"convert_to_unit2" => "required|exists:units,uuid",
			"convert_to_unit3" => "nullable|exists:units,uuid",
			"unit1_multiplier" => "required|numeric",
			"unit2_multiplier" => "required|numeric",
			"unit3_multiplier" => "required|numeric",
			"item_gross_weight" => "required|numeric",
			
			"pallet_tie" => "nullable|integer",
			"kit_parent_cost" => "nullable|numeric",
			"shelve_life" => "nullable|integer",
			"safety_stock" => "nullable|numeric",
			"safety_stock_unit" => "nullable|exists:units,uuid",
			"par_level" => "nullable",
			"par_level_unit" => "nullable|exists:units,uuid",
			"minimum_blend_amount" => "nullable|integer",
			"is_global" => "nullable|in:0,1",
			"is_kit_parent" => "nullable|in:0,1",
			"is_high_risk" => "nullable|in:0,1",
			"cost_item" => "nullable|in:0,1",

			"allergen_ids" => "nullable|array",
			"allergen_ids.*" => "exists:allergens,uuid"
		];
	}

	protected function multiDelete()
	{
		return [
			"ids" => 
				[ 
					"required",
					"array",
					new ProductDeleteRule()
				],
			"ids.*" =>"exists:products,uuid"
		];
	}
}
