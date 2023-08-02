<?php 
namespace App\Services\Tenant\Inventory;


use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Tenant\Inventory\ShipToRequest;
use App\Models\Tenant\{Customer,ShipTo,PrimaryContact};
use App\Http\Resources\Tenant\Inventory\ShipTo\ShipToResource;


class ShipToService 
{
	public function save($request)
	{
		$customer = Customer::findByUUID($request->customer_id);

		DB::beginTransaction();
		try {

			$primary_contact = new PrimaryContact;
			$primary_contact = $primary_contact->createPrimaryContact($request->all());
			$ship_to = ShipTo::create([
				'customer_id'	=> $customer->id,
				'name' 			=> $request->name,
				'external_id'   => $request->external_id,
				'address1'      => $request->address1,
				'address2'      => $request->address2,
				'city'          => $request->city,
				'state'         => $request->state,
				'zip_code'      => $request->zip_code,
				'status'        => $request->status,
				'primary_contact_id' => $primary_contact->id,
			]);
			
			DB::commit();
			
			$data = ['ship_to' => new ShipToResource($ship_to)];
			return json_response(200, __('Tenant.ship_to_save_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.ship_to_save_fail'));
		}
	}


    public function update($request){
        $ship_to = ShipTo::findByUUID($request->uuid);
		$customer = Customer::findByUUID($request->customer_id);

		DB::beginTransaction();
		try {
			$ship_to->primaryContact()->updatePrimaryContact($request->all());
			
			$ship_to->update([
				'customer_id'	=> $customer->id,
				'name' 			=> $request->name,
				'external_id'   => $request->external_id,
				'address1'      => $request->address1,
				'address2'      => $request->address2,
				'city'          => $request->city,
				'state'         => $request->state,
				'zip_code'      => $request->zip_code,
				'status'        => $request->status
			]);
			
			DB::commit();

			$ship_to = ShipTo::findByUUID($request->uuid);
			$data = ["ship_to" => new ShipToResource($ship_to)];
			return json_response(200, __('Tenant.ship_to_update_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.ship_to_update_fail'));
		}
    }

}