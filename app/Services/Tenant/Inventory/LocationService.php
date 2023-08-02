<?php 
namespace App\Services\Tenant\Inventory;


use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Tenant\{Location};
use App\Http\Resources\Tenant\Inventory\Location\LocationResource;


class LocationService 
{
	public function save($request)
	{
		DB::beginTransaction();
		try {
			$location = Location::create([
				'name' => $request->name,
				'barcode' => $request->barcode,
				'custom_capacity' => $request->custom_capacity,
				'is_remote_pick' => $request->remote_pick,
				'is_allergen_pick' => $request->allergen_pick,
				'is_tall_location' => $request->tall_location,
				'status' => $request->status,
			]);

			DB::commit();
			
			$data = ['location' => new LocationResource($location)];
			return json_response(200, __('Tenant.location_save_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.location_save_fail'));
		}
	}

    public function update($request){
        $location = Location::findByUUID($request->uuid);
        DB::beginTransaction();
		try {
			$location->update([
				'name' => $request->name,
				'barcode' => $request->barcode,
				'custom_capacity' => $request->custom_capacity,
				'is_remote_pick' => $request->remote_pick,
				'is_allergen_pick' => $request->allergen_pick,
				'is_tall_location' => $request->tall_location,
				'status' => $request->status,
			]);

			DB::commit();
			
            $location = Location::findByUUID($request->uuid);
			$data = ['location' => new LocationResource($location)];
			return json_response(200, __('Tenant.location_update_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.location_save_fail'));
		}
    }
}