<?php

namespace App\Http\Controllers\Tenant\Accounting;

use Exception;
use App\Models\Tenant\Utype;
use App\Models\Tenant\Pricing;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\PricingRequest;
use App\Http\Resources\Tenant\Accounting\PricingCollection;
use App\Http\Resources\Tenant\Accounting\PricingResource;

class PricingController extends Controller
{
	public function addNewPricing(PricingRequest $request)
	{
		$unit_type = Utype::where('name', 'pricing')->first();
		$unit = $unit_type->units()->where('uuid', $request->unit_id)->first();
		abort_if($unit == NULL, 403, "Invalid Unit Id");

		DB::beginTransaction();
		try {
			Pricing::create(array_merge($request->all(), [
				'status' => @$request->status ?? 0,
				'customer_id' => customerId($request->customer_id),
				'category_id' => categoryId($request->category_id),
				'product_id' => productId($request->product_id),
				'pricing_type_id' => pricingTypeId($request->pricing_type_id),
				'charge_type_id' => ChargeTypeId($request->charge_type_id),
				'unit_id' => $unit->id,
			]));
			DB::commit();
			return json_response(200, __("Tenant.add_new_pricing_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.add_new_pricing_fail'));
		}
	}

	public function deletePricing(PricingRequest $request)
	{
		$pricing = Pricing::findByUUIDOrFail($request->pricing_uuid);
		$pricing_reassign = Pricing::findByUUIDOrFail($request->pricing_reassign_uuid);
		abort_if($pricing_reassign->uuid === (string)$pricing->uuid || $pricing_reassign->status === (int)0, 422, __("Tenant.pricing_reassign_error"));

		return json_response(200, __("Tenant.delete_pricing"), $pricing->delete());
	}

	public function getSinglePricing(PricingRequest $request)
	{
		return json_response(
			200,
			__("Tenant.single_pricing"),
			new PricingResource(Pricing::findByUUIDOrFail($request->pricing_uuid))
		);
	}

	public function pricingList(PricingRequest $request)
	{
		$pricing = new Pricing;

		$pricing = isset($request->status) ? $pricing->where("status", $request->status) : $pricing;
		$pricing = $request->price_type_uuid ? $pricing->pricingTypeString($request->price_type_uuid) : $pricing;
		$pricing = $request->search ? $pricing->whereSearch($request->search) : $pricing;

		return json_response(200, __("Tenant.pricing_get_list"), ["pricing" => new PricingCollection($pricing->get())]);
	}

	public function updatePricing(PricingRequest $request)
	{
		$unit_type = Utype::where('name', 'pricing')->first();
		$unit = $unit_type->units()->where('uuid', $request->unit_id)->first();
		abort_if($unit == NULL, 403, "Invalid Unit Id");

		$pricing = Pricing::findByUUIDOrFail($request->uuid);
		DB::beginTransaction();
		try {
			$pricing->update(array_merge($request->all(), [
				'status' => @$request->status ?? 0,
				'customer_id' => customerId($request->customer_id),
				'category_id' => categoryId($request->category_id),
				'product_id' => productId($request->product_id),
				'pricing_type_id' => pricingTypeId($request->pricing_type_id),
				'charge_type_id' => ChargeTypeId($request->charge_type_id),
				'unit_id' => unitId($request->unit_id),
			]));
			DB::commit();
			return json_response(200, __("Tenant.update_pricing_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.update_pricing_fail'));
		}
	}
}
