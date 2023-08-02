<?php 
namespace App\Services\Tenant\Inventory;


use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Tenant\Inventory\ShipperRequest;
use App\Models\Tenant\{Shipper,PrimaryContact};
use App\Http\Resources\Tenant\Inventory\Shipper\ShipperResource;


class ShipperService 
{
	public function save($request)
	{
		DB::beginTransaction();
		try {

			$primary_contact = new PrimaryContact;
			$primary_contact = $primary_contact->createPrimaryContact($request->all());
			$shipper = Shipper::create(array_merge($request->all(), ['primary_contact_id' => $primary_contact->id]));
			
			DB::commit();
			
			$data = ['shipper' => new ShipperResource($shipper)];
			return json_response(200, __('Tenant.shipper_save_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.shipper_save_fail'));
		}
	}

    public function update($request){
        $shipper = Shipper::findByUUID($request->uuid);
		DB::beginTransaction();
		try {
			$shipper->primaryContact()->updatePrimaryContact($request->all());
			$shipper->update($request->all());
			
			DB::commit();

			$shipper = Shipper::findByUUID($request->uuid);
			$data = ["shipper" => new ShipperResource($shipper)];
			return json_response(200, __('Tenant.shipper_update_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.shipper_update_fail'));
		}
    }
}