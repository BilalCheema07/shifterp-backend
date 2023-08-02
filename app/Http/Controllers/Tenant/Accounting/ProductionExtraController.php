<?php

namespace App\Http\Controllers\Tenant\Accounting;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\ProductionExtra;
use App\Http\Requests\Tenant\Accounting\ProductionExtraRequest;
use App\Http\Resources\Tenant\Accounting\ProductionExtraCollection;
use App\Http\Resources\Tenant\Accounting\ProductionExtraResource;
use App\Models\Tenant\Utype;

class ProductionExtraController extends Controller
{

	public function listingProductionExtra(ProductionExtraRequest $request)
	{
		$prod_extra = new ProductionExtra;

		$prod_extra = isset($request->status) ? $prod_extra->where("status", $request->status) : $prod_extra;
		$prod_extra = isset($request->direct_material) ? $prod_extra->where("direct_material", $request->direct_material) : $prod_extra;
		$prod_extra = $request->search ? $prod_extra->whereSearch($request->search) : $prod_extra;

		return json_response(200, __("Tenant.production_extra_get_list"), ["production_extras" => new ProductionExtraCollection($prod_extra->get())]);
	}

	public function addNewProductionExtra(ProductionExtraRequest $request)
	{
		$unit_type = Utype::where('name', 'production_extra')->first();
		$unit = $unit_type->units()->where('uuid', $request->unit_id)->first();
		abort_if($unit == NULL, 403, "Invalid Unit Id");

		DB::beginTransaction();
		try {
			ProductionExtra::create(array_merge($request->all(), [
				'unit_id' => unitId($request->unit_id),
				'direct_material' => @$request->direct_material ?? 0,
				'status' => @$request->status ?? 0
			]));	
			DB::commit();
			return json_response(200, __("Tenant.add_new_production_extra_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return $e->getMessage();
			return json_response(500, __('Tenant.add_new_production_extra_fail'));
		}
	}
	
	public function getSingleProductionExtra(ProductionExtraRequest $request)
	{
		return json_response(
			200,
			__("Tenant.single_production_extra"),
			new ProductionExtraResource(ProductionExtra::findByUUIDOrFail($request->production_extra_uuid))
		);
	}
	
	public function deleteProductionExtra(ProductionExtraRequest $request)
	{
		$prod_extras = ProductionExtra::getByUUID($request->production_extra_uuid);
		foreach ($prod_extras as $prod_extra) {
			$prod_extra->delete();
			return json_response(200, __("Tenant.delete_production_extra"));
		}
	}

	public function updateProductionExtra(ProductionExtraRequest $request)
	{
		$unit_type = Utype::where('name', 'production_extra')->first();
		$unit = $unit_type->units()->where('uuid', $request->unit_id)->first();
		abort_if($unit == NULL, 403, "Invalid Unit Id");

		$prod_extra = ProductionExtra::findByUUIDOrFail($request->uuid);
		DB::beginTransaction();
		try {
			$prod_extra->update(array_merge($request->all(), [
				'unit_id' => unitId($request->unit_id),
				'direct_material' => @$request->direct_material ?? 0,
				'status' => @$request->status ?? 0
			]));	
			DB::commit();
			return json_response(200, __("Tenant.update_production_extra_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.update_production_extra_fail'));
		}
	}
}
