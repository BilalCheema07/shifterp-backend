<?php

namespace App\Http\Resources\Tenant\Inventory\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		// return ProductResource::collection($this->collection);
		return $this->getData();
	}


	public function getData()
	{
		$data = [];
		foreach ($this->collection as $item) {
			$data[] = [
				"uuid" => $item->uuid,
				"name" => $item->name,
				"description" => $item->description,
				"internal_name" => $item->internal_name,
				"internal_description" => $item->internal_description,
				"barcode" => $item->barcode,
				"universal_product_code" => $item->universal_product_code,
				"status" => $item->status,
				// "unit" => new ProductUnitResource($item->unit),
				"shipping" => new ProductShippingResource($item->shipping),
				"allergens" => ProductAllergenResource::collection($item->allergens),
				"customer" => new ProductCustomerResource($item->customer),
				"category" => new ProductCategoryResource($item->category)
			];
		}
		return $data;
	}
}
