<?php

namespace App\Http\Controllers\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Location;
use App\Http\Resources\Tenant\Inventory\Location\LocationCollection;
use App\Services\Tenant\Inventory\LocationService;

use App\Http\Requests\Tenant\Inventory\LocationRequest;
use App\Http\Resources\Tenant\Inventory\Location\LocationResource;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $service;
	function __construct(LocationService $service)
	{
		$this->service = $service;
	}
	
	public function list(LocationRequest $request)
	{
		$locations = new Location;

		$locations = isset($request->status) ? $locations->where("status", $request->status) : $locations;
		$locations = isset($request->allergen_pick) ? $locations->where("is_allergen_pick", $request->allergen_pick) : $locations;
		$locations = isset($request->tall_location) ? $locations->where("is_tall_location", $request->tall_location) : $locations;
		$locations = isset($request->remote_pick) ? $locations->where("is_remote_pick", $request->remote_pick) : $locations;

		$locations = $request->search ? $locations->whereSearch($request->search) : $locations;
		
		$locations = $locations->get();
        
		$data = ["locations" => new LocationCollection($locations)];
		return json_response(200, __("Tenant.location_get_list"), $data);
	}
	
	public function save(LocationRequest $request)
	{
		return $this->service->save($request);
	}

	public function get(LocationRequest $request)
	{
		$location = Location::findByUUID($request->uuid);
		$data = ["location" => new LocationResource($location)];
		return json_response(200, __("Tenant.location_get_single"), $data);;
	}
	
	public function update(LocationRequest $request)
	{
		return $this->service->update($request);
	}

	
	public function delete(LocationRequest $request)
	{
		$locations = Location::whereInUUID($request->ids)->get();
		
		DB::beginTransaction();

		try {
			foreach ($locations as $location) {
				$location->delete();
			}

		DB::commit();
			
			return json_response(200, __("Tenant.location_multi_del_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.location_multi_del_fail"));
		}
	}
}
