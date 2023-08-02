<?php

namespace App\Http\Controllers\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Shipper;
use App\Http\Resources\Tenant\Inventory\Shipper\ShipperCollection;
use App\Services\Tenant\Inventory\ShipperService;
use App\Http\Requests\Tenant\Inventory\ShipperRequest;
use App\Http\Resources\Tenant\Inventory\Shipper\ShipperResource;


class ShipperController extends Controller
{
    protected $service;
	function __construct(ShipperService $service)
	{
		$this->service = $service;
	}
	
	public function list(ShipperRequest $request)
	{
		$shipper = new Shipper;

		$shipper = isset($request->status) ? $shipper->where("status", $request->status) : $shipper;
		$shipper = isset($request->external_id) ? $shipper->where("external_id", $request->external_id) : $shipper;
		$shipper = $request->search ? $shipper->searchString($request->search) : $shipper;
		
		$shipper = $shipper->get();
        
		$data = ["shipper" => new ShipperCollection($shipper)];
		return json_response(200, __("Tenant.shipper_get_list"), $data);
	}
	
	public function save(ShipperRequest $request)
	{
		return $this->service->save($request);
	}

	public function get(ShipperRequest $request)
	{
		$shipper = Shipper::findByUUID($request->uuid);
		$data = ["shipper" => new ShipperResource($shipper)];
		return json_response(200, __("Tenant.shipper_get_single"), $data);
	}
	
	public function update(ShipperRequest $request)
	{
		return $this->service->update($request);
	}

	
	public function delete(ShipperRequest $request)
	{
		$shipper = Shipper::findByUUID($request->id);
		$shipper_reassign = Shipper::findByUUID($request->shipper_reassign);
		if($shipper_reassign->uuid == $shipper->uuid ){
			return json_response(422, __("Tenant.same_shipper_error"));
		}
		elseif( $shipper_reassign->status == 0 ){
			return json_response(422, __("Tenant.inactive_shipper"));
		}
		DB::beginTransaction();

		try {
			$shipper->delete();
			$shipper->primaryContact->delete();
	
			DB::commit();
					
			return json_response(200, __("Tenant.shipper_del_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.shipper_del_fail"));
		}
	}
}
