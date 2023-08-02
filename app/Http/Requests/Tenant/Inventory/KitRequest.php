<?php

namespace App\Http\Requests\Tenant\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class KitRequest extends FormRequest
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
				return $this->show();
			case "save":
				return $this->save();
			case "update":
				return $this->update();
			case "add-alternative-product":
				return $this->alternativeProducts();
			case "products-reorder":
				return $this->productReorder();
			case "product-remove":
				return $this->productRemove();
			default:
				return $this->multiDelete();
		}
	}

	protected function list()
	{
		return [
			"customer_id" => "nullable|array",
			"customer_id.*" => "exists:customers,uuid",
			"product_id" => "nullable|exists:products,uuid",
			"kit_id" => "nullable|exists:kits,uuid",
			"search" => "nullable|string"
		];
	}

	protected function show()
	{
		return [
			"uuid" => "required|exists:kits,uuid"
		];
	}

	protected function save()
	{
		return [
			"customer_id" => "required|exists:customers,uuid",
			"name" => "required|string|unique:kits,name",
			"description" => "required",
			"products" => "required|array",
			"products.*.product_id" => "required|exists:products,uuid",
			"products.*.part_type_id" => "required|exists:part_types,uuid",
			"products.*.unit_id" => "required|exists:units,uuid",
			"products.*.amount" => "required|integer",
		];
	}

	protected function update()
	{
		return [
			"uuid" => "required|exists:kits,uuid",
			"name" => "required|string|unique:kits,name,{$this->uuid},uuid",
			"products" => "required|array",
			"products.*.product_id" => "required|exists:products,uuid",
			"products.*.part_type_id" => "required|exists:part_types,uuid",
			"products.*.unit_id" => "required|exists:units,uuid",
			"products.*.amount" => "required|integer",

			"products.*.alternatives" => "nullable|array",
			"products.*.alternatives.*.product_id" => "required|exists:products,uuid",
			"products.*.alternatives.*.unit_id" => "required|exists:units,uuid",
			"products.*.alternatives.*.part_type_id" => "required|exists:part_types,uuid",
			"products.*.alternatives.*.amount" => "required|integer",
			"products.*.alternatives.*.priority" => "required|integer",

		];
	}

	protected function multiDelete()
	{
		return [
			"ids" => "required|array",
			"ids.*" => "exists:kits,uuid"
		];
	}
	
	protected function alternativeProducts()
	{
		return [
			"uuid" => "required|exists:kit_products,uuid",
			"products.*.product_id" => "required|exists:products,uuid",
			"products.*.amount" => "required|integer"
		];
	}
	protected function productReorder()
	{
		return [
			"alter_products.*.uuid" => "required|exists:kit_products,uuid",
			"alter_products.*.priority" => "required|integer"

		];
	}
	
	protected function productRemove()
	{
		return [
			"uuid" => "required|exists:kit_products,uuid",
		];
	}
}
