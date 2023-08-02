<?php 
namespace App\Services\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Tenant\Inventory\Product\ProductResource;
use App\Models\Tenant\{Allergen, Unit, Category, Customer, Product, ProductUnit, ProductShipping};

class ProductService 
{
	private function getUnitIdByUUID($uuid)
	{
		return Unit::findByUUID($uuid)->id;
	}

	public function save($request)
	{
		$customer = Customer::findByUUID($request->customer_id);
		$category = Category::findByUUID($request->category_id);

		DB::beginTransaction();
		try {
			$product = Product::create(array_merge($request->all(), [
				"customer_id" => $customer->id,
				"category_id" => $category->id
			]));

			ProductShipping::create(array_merge($request->all(), [
				"product_id" => $product->id,
				"pallet_tie" => @$request->pallet_tie == "" ? 0 : $request->pallet_tie,
				"kit_parent_cost" => @$request->kit_parent_cost == "" ? "0.0000" : $request->kit_parent_cost,
				"shelve_life" => @$request->shelve_life == "" ? 0 : $request->shelve_life,
				"safety_stock" => @$request->safety_stock == "" ? "0.0000" : $request->safety_stock,
				"par_level" => @$request->par_level == "" ? "0.0000" : $request->par_level,
				"minimum_blend_amount" => @$request->minimum_blend_amount == "" ? 0 : $request->minimum_blend_amount,
				"safety_stock_unit" => $request->safety_stock_unit ? $this->getUnitIdByUUID($request->safety_stock_unit) : null,
				"par_level_unit" => $request->par_level_unit ? $this->getUnitIdByUUID($request->par_level_unit) : null,
			]));

			ProductUnit::create(array_merge($request->all(), [
				"product_id" => $product->id,
				"unit_of_stock" => $this->getUnitIdByUUID($request->unit_of_stock),
				"unit_of_order" => $this->getUnitIdByUUID($request->unit_of_order),
				"unit_of_count" => $this->getUnitIdByUUID($request->unit_of_count),
				"unit_of_package" => $this->getUnitIdByUUID($request->unit_of_package),
				"unit_of_sell" => $this->getUnitIdByUUID($request->unit_of_sell),
				"unit_of_assembly" => $request->unit_of_assembly ? $this->getUnitIdByUUID($request->unit_of_assembly) : null,
				"unit_of_purchase" => $request->unit_of_purchase ? $this->getUnitIdByUUID($request->unit_of_purchase) : null,
				"variable_unit1" => $request->variable_unit1 ? $this->getUnitIdByUUID($request->variable_unit1) : null,
				"variable_unit2" => $request->variable_unit2 ? $this->getUnitIdByUUID($request->variable_unit2) : null,
				"convert_to_unit1" => $this->getUnitIdByUUID($request->convert_to_unit1),
				"convert_to_unit2" => $this->getUnitIdByUUID($request->convert_to_unit2),
				"convert_to_unit3" => $request->convert_to_unit3 ? $this->getUnitIdByUUID($request->convert_to_unit3) : null
			]));

			if ((@$request->allergen_ids) > 0) {
				$allergen_ids = Allergen::getByUUID($request->allergen_ids)->pluck("id");
				$product->allergens()->sync($allergen_ids);
			}
			DB::commit();

			$data = ["product" => new ProductResource($product)];
			return json_response(200, __("Tenant.product_save_success"), $data);	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.product_save_fail"));
		}
	}

	public function update($request)
	{	
		$customer = Customer::findByUUID($request->customer_id);
		$category = Category::findByUUID($request->category_id);
		
		DB::beginTransaction();
		try {
			$product = Product::findByUUID($request->uuid);

			$product->update(array_merge($request->all(), [
				"customer_id" => $customer->id,
				"category_id" => $category->id
			]));
			
			$product->shipping->update(array_merge($request->all(), [
				"pallet_tie" => @$request->pallet_tie == "" ? 0 : $request->pallet_tie,
				"kit_parent_cost" => @$request->kit_parent_cost == "" ? "0.0000" : $request->kit_parent_cost,
				"shelve_life" => @$request->shelve_life == "" ? 0 : $request->shelve_life,
				"safety_stock" => @$request->safety_stock == "" ? "0.0000" : $request->safety_stock,
				"par_level" => @$request->par_level == "" ? "0.0000" : $request->par_level,
				"minimum_blend_amount" => @$request->minimum_blend_amount == "" ? 0 : $request->minimum_blend_amount,
				"safety_stock_unit" => $request->safety_stock_unit ? $this->getUnitIdByUUID($request->safety_stock_unit) : null,
				"par_level_unit" => $request->par_level_unit ? $this->getUnitIdByUUID($request->par_level_unit) : null,
			]));

			$product->unit->update(array_merge($request->all(), [
				"unit_of_stock" => $this->getUnitIdByUUID($request->unit_of_stock),
				"unit_of_order" => $this->getUnitIdByUUID($request->unit_of_order),
				"unit_of_count" => $this->getUnitIdByUUID($request->unit_of_count),
				"unit_of_package" => $this->getUnitIdByUUID($request->unit_of_package),
				"unit_of_sell" => $this->getUnitIdByUUID($request->unit_of_sell),
				"unit_of_assembly" => $request->unit_of_assembly ? $this->getUnitIdByUUID($request->unit_of_assembly) : null,
				"unit_of_purchase" => $request->unit_of_purchase ? $this->getUnitIdByUUID($request->unit_of_purchase) : null,
				"variable_unit1" => $request->variable_unit1 ? $this->getUnitIdByUUID($request->variable_unit1) : null,
				"variable_unit2" => $request->variable_unit2 ? $this->getUnitIdByUUID($request->variable_unit2) : null,
				"convert_to_unit1" => $this->getUnitIdByUUID($request->convert_to_unit1),
				"convert_to_unit2" => $this->getUnitIdByUUID($request->convert_to_unit2),
				"convert_to_unit3" => $request->convert_to_unit3 ? $this->getUnitIdByUUID($request->convert_to_unit3) : null
			]));

			$allergen_ids = array();
			if ((@$request->allergen_ids) > 0) {
				$allergen_ids = Allergen::getByUUID($request->allergen_ids)->pluck("id");
			}
			$product->allergens()->sync($allergen_ids);

			DB::commit();
			
			$data = ["product" => new ProductResource($product)];
			return json_response(200, __("Tenant.product_update_success"), $data);	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.product_update_fail"));
		}
	}
}
