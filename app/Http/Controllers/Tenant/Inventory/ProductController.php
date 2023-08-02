<?php

namespace App\Http\Controllers\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Services\Tenant\Inventory\ProductService;
use App\Http\Requests\Tenant\Inventory\ProductRequest;
use App\Models\Tenant\{Allergen, Category, Product, Utype,Customer,Kit, KitProduct};
use App\Http\Resources\Tenant\Inventory\Product\{ProductResource, ProductCollection};
use App\Http\Resources\Tenant\Inventory\Allergen\AllergenCollection;
use App\Http\Resources\Tenant\Inventory\Category\CategoryCollection;
use App\Http\Resources\Tenant\Unit\UtypeCollection;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
	protected $service;

	function __construct(ProductService $service)
	{
		$this->service = $service;
	}

	public function dependencies()
	{
		$unit_types = Utype::with("units")->get();
		$categories = Category::all();
		$allergens = Allergen::all();
		$customers = Customer::where('status',1)->select('uuid','code')->get();

		$data = [
			"unit_types" => new UtypeCollection($unit_types),
			"categories" => new CategoryCollection($categories),
			"allergens" => new AllergenCollection($allergens),
			"customers" => $customers,
		];
		return json_response(200, __("Tenant.product_get_dependencies"), $data);
	}

	public function list(ProductRequest $request)
	{
		$products = new Product;

		$products = $request->search ? $products->searchString($request->search) : $products;
		
		if (@$request->customer_id > 0) {
			$products = @$request->customer_id ? $products->whereCustomerUUIDs($request->customer_id) : $products;
		}

		if (@$request->category_id > 0) {
			$products = @$request->category_id ? $products->whereCategoryUUIDs($request->category_id) : $products;
		}
		// dd($products->with('category')->get());
		$products = @$request->allegen_id ? $products->whereAllergenUUID($request->allegen_id) : $products;
		$products = isset($request->status) ? $products->where("status", $request->status) : $products;
		$products = isset($request->high_risk) ? $products->whereHighRisk($request->high_risk) : $products;
		$products = isset($request->costed) ? $products->whereCosted($request->costed) : $products;

		$products = $products->orderBy("id", $request->order ?? "asc")
			->with("shipping", "allergens", "customer", "category")
			->get();

		$data = ["products" => new ProductCollection($products)];
		return json_response(200, __("Tenant.product_get_list"), $data);
	}

	public function get(ProductRequest $request)
	{
		$product = Product::findByUUID($request->uuid);

		$data = ["product" => new ProductResource($product)];
		return json_response(200, __("Tenant.product_get_single"), $data);	
	}

	public function save(ProductRequest $request)
	{
		return $this->service->save($request);
	}

	public function update(ProductRequest $request)
	{
		return $this->service->update($request);
	}

	public function delete(ProductRequest $request)
	{
		DB::beginTransaction();
		try {
			$products = Product::getByUUID($request->ids);
			
			foreach ($products as $product) {
					$product->allergens()->detach();
					$product->shipping()->delete();
					$product->unit()->delete();
					$product->delete();
			}
			DB::commit();
			
			return json_response(200, __("Tenant.product_multi_del_success"));	
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.product_multi_del_fail"));
		}
	}
}
