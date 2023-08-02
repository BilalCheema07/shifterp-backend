<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvisionAccountRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}
	
	public function rules()
	{
		switch (last(request()->segments())) {
			case "store":
				return $this->store();
			case "update":
				return $this->update();
			case "change-status":
				return $this->status();
			case "upload-sow";
				return $this->uploadSow();
			default:
				return $this->subscriptions();
		}
	}
	protected function store()
	{
		return [
			//Provision Account
			'company_name'			=> 'required|unique:provision_accounts,company_name',
			'dba_name'				=> 'required',
			'subscription_id'		=> 'required|exists:subscriptions,id',	
			'provision_address'		=> 'required',
			'provision_city'		=> 'required',
			'provision_phone'		=> 'required',
			'provision_state'		=> 'required',
			'provision_zip'			=> 'required',
			'provision_status'		=> 'nullable|in:0,1',

			//Billing Contact
			'billing_fname'			=> 'required',
			'billing_lname'			=> 'required',
			'billing_title'			=> 'required',
			'billing_email'			=> 'required',
			'billing_phone'			=> 'required',
			'billing_address'		=> 'required',
			'billing_city' 			=> 'required',
			'billing_state' 		=> 'required',
			'billing_zip' 			=> 'required',

			//Subscriptions
			'subscription_id'		=> 'required|exists:subscriptions,uuid',
			'recurring_billing_start_date'	=> 'required',
			'setup_fee' 			=> 'required',
			'setup_fee_start_date' 	=> 'required',

			//User
			'fname'					=> 'required',
			'lname'					=> 'required',
			'username'				=> 'required|unique:users,username',
			'phone'					=> 'required',
			'email'					=> 'required|unique:users,email',
		];
	}
	protected function update()
	{
		return [
			'subscription_id'		=> 'required|exists:subscriptions,uuid',
			'dba_name'				=> 'required',
			'provision_address'		=> 'required',
			'provision_city'		=> 'required',
			'provision_phone'		=> 'required',
			'provision_state'		=> 'required',
			'provision_zip' 		=> 'required',
			'provision_status' 		=> 'nullable|in:0,1',

			//Billing Contact
			'billing_fname'			=> 'required',
			'billing_lname'			=> 'required',
			'billing_title'			=> 'required',
			'billing_email'			=> 'required',
			'billing_phone'			=> 'required',
			'billing_address'		=> 'required',
			'billing_city'			=> 'required',
			'billing_state'			=> 'required',
			'billing_zip'			=> 'required',

			//Subscriptions
			'subscription_id'		=> 'required|exists:subscriptions,uuid',
			'recurring_billing_start_date'=> 'required',
			'setup_fee'				=> 'required',
			'setup_fee_start_date' 	=> 'required',
		 	
			//User
			'fname'   				=> 'required',
			'lname' 				=> 'required',
			'phone'  				=> 'required',
		];
	}
	protected function status()
	{
		return [
			'status'				=> 'required|in:cancel,pause,active,in-processing',
			'provision_account_id'	=> 'required|exists:provision_accounts,uuid'
		];
	}

	protected function subscriptions()
	{
		return[
			'provision_account_id'	=> 'required|exists:provision_accounts,uuid'
		];
	}

	protected function uploadSow()
	{
		return [
			'sow'					=> 'required|mimes:pdf|max:5120',
			'provision_account_id'	=> 'required|exists:provision_accounts,uuid'
		];
	}
}
