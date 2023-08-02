<?php

namespace App\Http\Controllers\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Services\Tenant\Inventory\KitService;
use App\Http\Requests\Tenant\Inventory\KitRequest;
use App\Http\Resources\Tenant\Unit\UtypeCollection;
use App\Http\Resources\Tenant\Inventory\PartType\PartTypeCollection;
use App\Http\Resources\Tenant\Inventory\Kit\{KitResource, KitCollection};
use App\Models\Tenant\{Kit, Customer, KitProduct, PartType, Product, Utype};

class KitController extends Controller
{
	protected $service;
	function __construct(KitService $service)
	{
		$this->service = $service;
	}

	public function dependencies()
	{
		$unit_types = Utype::where("name", "kit")->with("units")->get();
		$products = Product::select("uuid", "name", "description", "status")->get();
		$customers = Customer::select("uuid", "name", "code")->get();
		$part_types = PartType::all();
		
		$data = [
			"customers" => $customers,
			"products" => $products,
			"unit_types" => new UtypeCollection($unit_types),
			"part_types" => new PartTypeCollection($part_types),
		];
		return json_response(200, __("Tenant.kit_get_dependencies"), $data);
	}

	public function list(KitRequest $request)
	{
		$kits = new Kit;

		if(@$request->customer_id > 0){
			$kits = $request->customer_id ? $kits->whereCustomerUUIDs($request->customer_id) : $kits;
		}
		$kits = $request->product_id ? $kits->whereProductUUID($request->product_id) : $kits;
		$kits = $request->kit_id ? $kits->whereIn("uuid",$request->kit_id) : $kits;
		$kits = $request->search ? $kits->whereSearch($request->search) : $kits;
		
		$kits = $kits->with("customer", "kitProducts", "kitProducts.partType", "kitProducts.product")->get();
		
		$data = ["kits" => new KitCollection($kits)];
		return json_response(200, __("Tenant.kit_get_list"), $data);
	}

	public function get(KitRequest $request)
	{
		$kit = Kit::findByUUID($request->uuid);

		$data = ["kit" => new KitResource($kit)];
		return json_response(200, __("Tenant.kit_get_single"), $data);
	}

	public function save(KitRequest $request)
	{
		return $this->service->save($request);
	}

	public function update(KitRequest $request)
	{
		return $this->service->update($request);
	}

	public function delete(KitRequest $request)
	{
		$kits = Kit::whereInUUID($request->ids)->with("kitProducts")->get();

		DB::beginTransaction();
		try {
			foreach ($kits as $kit) {
				$kit->kitProducts()->delete();
				$kit->delete();
			}
			DB::commit();
			
			return json_response(200, __("Tenant.kit_multi_del_success"));
		} catch (Exception $e) {
			DB::rollBack();
			if ($e->getcode() == "23000") {
				return json_response(403, __("Tenant.kit_multi_del_restricted"));
			}
			return json_response(500, __("Tenant.kit_multi_del_fail"));
		}
	}
	
	public function addAlternativeProducts(KitRequest $request)
	{
		$kit_product = KitProduct::findByUUID($request->uuid);
		$alternatives = KitProduct::where("parent_id",$kit_product->id)->orderBy("priority", "desc")->value("priority");
		$count = @$alternatives ? $alternatives+1 : 0;

		DB::beginTransaction();
		try {
			foreach ($request->products as $product) {
				$product = (object)$product;

				$product_detail = Product::findByUUID($product->product_id);
				
				DB::commit();
				if($product_detail->status == 1){

					KitProduct::create([
						"kit_id" => $kit_product->kit_id,
						"product_id" => $product_detail->id,
						"part_type_id" => $kit_product->part_type_id,
						"unit" => $kit_product->unit,
						"amount" => $product->amount,
						"parent_id" => $kit_product->id,
						"priority" => $count,
					]);
					$count ++;
				}
			}

		return json_response(200,  __("Tenant.kit_add_alternative"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500,  __("Tenant.kit_add_alternative_error"));
		}
	}

	public function productsReorder(KitRequest $request)
	{
		DB::beginTransaction();
		try {
			foreach ($request->alter_products as $alter_product) {
				$product_uuid = (object)$alter_product;

				DB::commit();
				KitProduct::where("uuid",$product_uuid->uuid)->update([
					"priority" => $product_uuid->priority
				]);
			}

		return json_response(200, __("Tenant.kit_product_reorder"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.kit_product_reorder_error"));
		}
	}

	public function productRemove(KitRequest $request)
	{
		$kit_product = KitProduct::findByUUID($request->uuid);
		$product_alternative = KitProduct::where("parent_id",$kit_product->id)->get();

		DB::beginTransaction();
		try {
			if(@$product_alternative)
			{
				foreach ($product_alternative as $children) {
					$children->delete();
				}
			}
			$kit_product->delete();

			DB::commit();

			return json_response(200, __("Tenant.kit_product_multi_del_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.kit_product_multi_del_fail"));
		}
	}

}
