<?php

namespace App\Http\Controllers\Tenant\Customer;

use App\Http\Controllers\Controller;

use App\Models\Tenant\{Customer};
use App\Http\Requests\Tenant\CustomerRequest;
use App\Http\Resources\Tenant\Customer\CustomerResource;
use App\Services\Tenant\Customers\CustomerService;

class CustomerController extends Controller
{
	protected $customer_service;

	public function __construct(CustomerService $customer_service)
	{
		$this->customer_service = $customer_service;
	}

	public function list(CustomerRequest $request)
	{
		return $this->customer_service->getList($request);
	}
	
	public function show(CustomerRequest $request)
	{
		// $customer = Customer::whereUUID($request->uuid)->with('primaryContact', 'facilities')->first();		
		// $data = ['customer' => $customer];
		
		// return json_response(200, __('Customer.get_single'), $data);
			$customer = Customer::findByUUIDOrFail($request->uuid);
			return json_response(200, __('Customer.get_single'), new CustomerResource($customer));
	}
	
	public function save(CustomerRequest $request)
	{
		return $this->customer_service->saveCustomer($request);
	}
	
	public function update(CustomerRequest $request)
	{
		return $this->customer_service->updateCustomer($request);
	}
	
	public function delete(CustomerRequest $request)
	{
		return $this->customer_service->deleteCustomer($request);
	}

	public function searchCode(CustomerRequest $request)
	{
		$customer = Customer::where('code', $request->code)->with('primaryContact')->first();	
		$data = ['customer' => $customer];
		return json_response(200, __('Customer.get_single'), $data);
	}
}
