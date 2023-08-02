<?php

namespace App\Http\Controllers\Tenant\Inventory;

use Exception;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Tenant\{ShipTo,Customer};
use App\Http\Resources\Tenant\Inventory\ShipTo\ShipToCollection;
use App\Services\Tenant\Inventory\ShipToService;
use App\Http\Requests\Tenant\Inventory\ShipToRequest;
use App\Http\Resources\Tenant\Inventory\ShipTo\ShipToResource;


class ShipToController extends Controller
{
    protected $service;
	function __construct(ShipToService $service)
	{
		$this->service = $service;
	}
	

	public function list(ShipToRequest $request)
	{
        $ship_to = new ShipTo;

		$ship_to = isset($request->status) ? $ship_to->where("status", $request->status) : $ship_to;
		$ship_to = isset($request->external_id) ? $ship_to->where("external_id", $request->external_id) : $ship_to;
		$ship_to = $request->search ? $ship_to->searchString($request->search) : $ship_to;

		$ship_to = $ship_to->get();

		$data = ["ship_to" => new ShipToCollection($ship_to)];
		return json_response(200, __("Tenant.ship_to_get_list"), $data);
	}

	public function save(ShipToRequest $request)
	{
		return $this->service->save($request);
	}


	public function get(ShipToRequest $request)
	{
		$ship_to = ShipTo::findByUUID($request->uuid);
		$data = ["ship_to" => new ShipToResource($ship_to)];
		return json_response(200, __("Tenant.ship_to_get_single"), $data);
	}
	
	public function update(ShipToRequest $request)
	{
		return $this->service->update($request);
	}
    public function delete(ShipToRequest $request)
	{
		$ship_to = ShipTo::findByUUID($request->id);
		$ship_to_reassign = ShipTo::findByUUID($request->ship_to_reassign);
		if($ship_to_reassign->uuid == $ship_to->uuid ){
			return json_response(422, __("Tenant.same_ship_to_error"));
		}
		elseif( $ship_to_reassign->status == 0 ){
			return json_response(422, __("Tenant.inactive_ship_to"));
		}
		DB::beginTransaction();

		try {
			$ship_to->delete();
			$ship_to->primaryContact->delete();
	
			DB::commit();
					
			return json_response(200, __("Tenant.ship_to_remove_success"));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __("Tenant.ship_to_remove_fail"));
		}
	}
    public function multiOption(ShipToRequest $request){
		$ship_tos = ShipTo::whereIn("uuid", $request->shipto_ids)->get();
        foreach ($ship_tos as $ship_to ) {
            if($request->action == 'active'){
                $ship_to->status = 1; 
            }
            elseif ($request->action == 'de-active') {
                $ship_to->status = 0;
            }elseif ($request->action == 'add-customer') {
                if($request->customer_id ){
                    $customer = Customer::findByUUID($request->customer_id);
                    $ship_to->customer_id = $customer->id;
                }else{
                    return json_response(422, __("Tenant.ship_to_customer_id_missing"));
                } 
            }

            $ship_to->save();
        }
        return json_response(200, __('Tenant.ship_to_update_success'));
    }
}
