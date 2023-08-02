<?php 
namespace App\Services\Tenant\Customers;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\{Customer, Facility, PrimaryContact,Product};
use App\Http\Resources\Tenant\Customer\{CustomerResource, CustomerCollection};

class CustomerService 
{
	public function getList($request)
	{
		$customers = new Customer;

		$customers = $request->search ? $customers->searchString($request->search) : $customers;
		$customers = isset($request->status) ? $customers->where('status', $request->status) : $customers;
		$customers = $customers->orderBy('id', $request->order ?? 'asc')->with('primaryContact', 'facilities')->get();

		$data = ["customer" => new CustomerCollection($customers)];
		return json_response(200, __('Customer.get_data'), $data);
	}

	public function saveCustomer($request)
	{
		DB::beginTransaction();
		try {
			$primary_contact = new PrimaryContact;
			$primary_contact = $primary_contact->createPrimaryContact($request->all());
			$customer = Customer::create(array_merge($request->all(), ['primary_contact_id' => $primary_contact->id]));

			if ((@$request->facility_ids) > 0) {
				$facility_ids = Facility::getByUUID($request->facility_ids)->pluck("id");
				$customer->facilities()->attach($facility_ids);
			}

			DB::commit();
			
			$data = ['customer' => new CustomerResource($customer)];
			return json_response(200, __('Customer.added'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Customer.error'));
		}
	}

	public function updateCustomer($request)
	{	
		$customer = Customer::findByUUID($request->uuid);
		DB::beginTransaction();
		try {
			$customer->primaryContact()->updatePrimaryContact($request->all());
			$customer->update($request->all());
			
			$customer->facilities()->detach();
			if ((@$request->facility_ids) > 0) {
				$facility_ids = Facility::getByUUID($request->facility_ids)->pluck("id");
				$customer->facilities()->attach($facility_ids);
			}
			DB::commit();

			$customer = Customer::findByUUID($request->uuid);
			$data = ["customer" => new CustomerResource($customer)];
			return json_response(200, __('Customer.updated'), $data);
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Customer.error'));
		}
	}

	public function deleteCustomer($request)
	{
		$customer = Customer::whereUUID($request->uuid)->with('primaryContact')->first();

		DB::beginTransaction();
		try {
			$customer->delete();
			$customer->primaryContact->delete();
			
			DB::commit();

			return json_response(200, __('Customer.removed'));
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, __('Customer.not_deleted'));
		}
	}
}
