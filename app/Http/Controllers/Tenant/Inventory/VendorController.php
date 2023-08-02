<?php

namespace App\Http\Controllers\Tenant\Inventory;


use Exception;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Vendor;
use App\Http\Resources\Tenant\Inventory\Vendor\VendorCollection;
use App\Services\Tenant\Inventory\VendorService;
use App\Http\Requests\Tenant\Inventory\VendorRequest;
use App\Http\Resources\Tenant\Inventory\Vendor\VendorResource;

class VendorController extends Controller
{
    protected $service;
	function __construct(VendorService $service)
	{
		$this->service = $service;
	}
    
	public function list(VendorRequest $request)
	{
		$vendor = new Vendor;
		
		$vendor = isset($request->status) ? $vendor->where("status", $request->status) : $vendor;
		$vendor = isset($request->vendor_ids) ? $vendor->whereIn("uuid", $request->vendor_ids) : $vendor;
		$vendor = $request->search ? $vendor->searchString($request->search) : $vendor;
		$vendor = $vendor->get();
        
		$data = ["vendor" => new VendorCollection($vendor)];
		return json_response(200, __("Tenant.vendor_get_list"), $data);
	}
	
	public function save(VendorRequest $request)
	{
		return $this->service->save($request);
	}

	public function get(VendorRequest $request)
	{
		$vendor = Vendor::findByUUID($request->uuid);
		$data = ["vendor" => new VendorResource($vendor)];
		return json_response(200, __("Tenant.vendor_get_single"), $data);
	}
	
	public function update(VendorRequest $request)
	{
		return $this->service->update($request);
	}

	public function delete(VendorRequest $request)
	{
		$vendor = Vendor::findByUUID($request->id);
		DB::beginTransaction();
		try {
			$vendor->delete();
			$vendor->primaryContact->delete();
		DB::commit();
			return json_response(200, __("Tenant.vendor_del_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.vendor_del_fail"));
		}
	}
}
