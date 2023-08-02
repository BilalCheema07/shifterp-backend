<?php 
namespace App\Services\Tenant\Inventory;


use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{Vendor,PrimaryContact};
use App\Http\Resources\Tenant\Inventory\Vendor\VendorResource;

class VendorService 
{
	public function save($request)
	{
		DB::beginTransaction();
		try {

			$primary_contact = new PrimaryContact;
			$primary_contact = $primary_contact->createPrimaryContact($request->all());
			$vendor = Vendor::create(array_merge($request->all(), ['primary_contact_id' => $primary_contact->id]));
			
			DB::commit();
			
			$data = ['vendor' => new VendorResource($vendor)];
			return json_response(200, __('Tenant.vendor_save_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.vendor_save_fail'));
		}
	}
	
    public function update($request)
	{
        $vendor = Vendor::findByUUID($request->uuid);
		DB::beginTransaction();
		try {
			$vendor->primaryContact()->updatePrimaryContact($request->all());
			$vendor->update($request->all());
			
			DB::commit();

			$vendor = Vendor::findByUUID($request->uuid);
			$data = ["vendor" => new VendorResource($vendor)];
			return json_response(200, __('Tenant.vendor_update_success'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Tenant.vendor_update_fail'));
		}
    }

}