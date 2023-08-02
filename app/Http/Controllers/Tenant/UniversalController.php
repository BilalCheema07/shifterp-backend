<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Tenant\{Customer, Product, Kit};
use App\Http\Resources\Tenant\Inventory\Kit\KitCollection;
use App\Http\Requests\Tenant\UniversalRequest;

class UniversalController extends Controller
{
    public function customers()
    {
        $customer = Customer::where('status', 1)->select('uuid', 'name')->get();
        return json_response(200, __("Tenant.universal_customers"),$customer);
    }

    public function moduleData(UniversalRequest $request)
    {
        $model_name = "App\Models\Tenant\\".ucfirst($request->module_name);
        if (class_exists($model_name)) {
            try{
                if ($request->module_name == 'user') {
                    $model_data = $model_name::where('id', '<>', auth()->user()->tenant_user_id)->select($request->fields)->get();
                } else {
                    $model_data = $model_name::select($request->fields)->get();
                }
            }
            catch(Exception $e){
                return json_response(403, __('Tenant.module_fields_invalid'));
            }
       } else {
        return json_response(403, __("Tenant.universal_model_error"));
       }
       return json_response(200, __("Tenant.module_record_fetch"), $model_data);
       
    }

    public function customerProducts(UniversalRequest $request)
    {
		$customer = Customer::findByUUID($request->customer_id);
        $products = Product::select("product_shippings.is_global","products.uuid", "products.name", "products.description","products.status")
		->leftJoin('customers', 'customers.id', '=', 'products.customer_id')
		->leftJoin('product_shippings', 'products.id', '=', 'product_shippings.product_id')
		->where('customers.status', 1)
		->where('products.status', 1)
		->where(function($query) use ($customer){
			$query->where('is_global', true)->orWhere('products.customer_id', $customer->id);
		})->get();

        return json_response(200, __('Tenant.get_customer_products'), $products);
	}
	
    public function customerKits(UniversalRequest $request)
    {
        $customer = Customer::findByUUID($request->customer_id);
           $kits = Kit::where('customer_id',$customer->id)->get();
           $data =  new KitCollection($kits);
       
            return json_response(200, 'kits fetch successfully.', $data); 
        }
}
