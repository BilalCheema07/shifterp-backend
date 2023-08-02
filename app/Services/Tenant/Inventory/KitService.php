<?php 
namespace App\Services\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Tenant\Inventory\Kit\KitResource;
use App\Models\Tenant\{Unit, Product, Kit, KitProduct, Customer, PartType};

class KitService 
{
	public function save($request)
	{
		$customer = Customer::findByUUID($request->customer_id);
		
		DB::beginTransaction();
		try {
			$kit = Kit::create([
				'customer_id' => $customer->id,
				'name' => $request->name,
				'description' => $request->description
			]);
			$this->addProductsInKit($request->products, $kit->id);

			DB::commit();
			
			$data = ['kit' => new KitResource($kit)];
			return json_response(200, __('Tenant.kit_save_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.kit_save_fail'));
		}
	}

	public function update($request)
	{
		$kit = Kit::findByUUID($request->uuid);
		DB::beginTransaction();
		try {
			$kit->update([
				'name' => $request->name,
			]);

			KitProduct::where('kit_id', $kit->id)->delete();
			$this->updateProductsInKit($request->products, $kit->id);

			DB::commit();
			
			$kit = Kit::findByUUID($request->uuid);
			$data = ['kit' => new KitResource($kit)];
			return json_response(200, __('Tenant.kit_update_success'), $data);
		} catch(Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.kit_update_fail'));
		}
	}

	protected function updateProductsInKit($products, $kit_id)
	{

		foreach ($products as $product) {
			$product = (object)$product;

			$product_id = Product::findByUUID($product->product_id)->id;
			$part_type_id = PartType::findByUUID($product->part_type_id)->id;
			$unit_id = Unit::findByUUID($product->unit_id)->id;

			$kit_product = KitProduct::create([
				'kit_id' => $kit_id,
				'product_id' => $product_id,
				'part_type_id' => $part_type_id,
				'unit_id' => $unit_id,
				'amount' => $product->amount,
			]);
			if(@$product->alternatives ){
				
			foreach ($product->alternatives as $alternative) {
				$alternative = (object)$alternative;

				$product_id = Product::findByUUID($alternative->product_id)->id;
				$part_type_id = PartType::findByUUID($product->part_type_id)->id;
				$unit_id = Unit::findByUUID($alternative->unit_id)->id;

					KitProduct::create([
						'kit_id' => $kit_id,
						'product_id' => $product_id,
						'part_type_id' => $part_type_id,
						'unit_id' => $unit_id ,
						'amount' => $alternative->amount,
						'parent_id' => $kit_product->id,
						'priority' => $alternative->priority,
					]);
			}
			}
		}
	}
	protected function addProductsInKit($products, $kit_id)
	{
		foreach ($products as $product) {
			$product = (object)$product;

			$product_id = Product::findByUUID($product->product_id)->id;
			$part_type_id = PartType::findByUUID($product->part_type_id)->id;
			$unit_id = Unit::findByUUID($product->unit_id)->id;

			KitProduct::create([
				'kit_id' => $kit_id,
				'product_id' => $product_id,
				'part_type_id' => $part_type_id,
				'unit_id' => $unit_id ,
				'amount' => $product->amount,
			]);
		}
	}
}
