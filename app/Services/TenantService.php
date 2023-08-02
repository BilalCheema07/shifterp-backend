<?php 
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProvisionAccountResource;
use Exception;
use App\Http\Resources\Tenant\CUserResource;
use App\Models\{Tenant, User, BillingContact, ProvisionAccount, Subscription, SubscriptionDetail, SubscriptionHistory};
use App\Models\Tenant\{User as CUser, Permission, Role};
use Carbon\Carbon;

class TenantService 
{
	public function addProvisionDetails($request,$new_tenant,$req_name)
	{	
		$sub = Subscription::findByUUID($request->subscription_id);
		DB::beginTransaction();
		try {
			$provision_account = ProvisionAccount::create([
				"company_name" 	=> $request->company_name,
				"tenant_id" 	=> $req_name,
				"dba_name" 		=> $request->dba_name,
				"address" 		=> $request->provision_address,
				"city" 			=> $request->provision_city,
				"phone" 		=> $request->provision_phone,
				"state" 		=> $request->provision_state,
				"zip" 			=> $request->provision_zip,
				"status" 		=> $request->provision_status,
			]);

			//Billing Contact
			BillingContact::create([
				"provision_account_id"	=> $provision_account->id,
				"fname"					=> $request->billing_fname,
				"lname"					=> $request->billing_lname,
				"title"					=> $request->billing_title,
				"email"					=> $request->billing_email,
				"contact_number"		=> $request->billing_phone,
				"address"				=> $request->billing_address,
				"city"					=> $request->billing_city,
				"state"					=> $request->billing_state,
				"zip"					=> $request->billing_zip
			]);

			//Subscription Details
			$subscription = SubscriptionDetail::create([
				"provision_account_id"			=> $provision_account->id,
				"subscription_id"				=> $sub->id,
				"recurring_billing_start_date"	=> date("Y-m-d H:i:s", strtotime($request->recurring_billing_start_date)),
				"setup_fee" 					=> $request->setup_fee,
				"setup_fee_start_date"			=> date("Y-m-d H:i:s" , strtotime($request->setup_fee_start_date)),
				"total" 						=> $sub->price_per_license + $request->setup_fee,
			]);
			
			//Subscription History
			SubscriptionHistory::create([
				"provision_account_id"		=> $provision_account->id,
				"subscription_detail_id"	=> $subscription->id,
				"billed_date"				=> Carbon::now(),
				"status"					=> 1,
				"amount"					=> $sub->price_per_license + $request->setup_fee
			]);
			
			//Initializing tenant
			tenancy()->initialize($new_tenant);
			
			$role = Role::where("slug", "company_admin")->first();
			
			$user = CUser::create([
				"fname"					=> $request->fname,
				"lname"					=> $request->lname,
				"username"				=> $request->username,
				"password"				=> Hash::make(trim(12345678)),
				"phone"					=> $request->phone,
				"email"					=> $request->email,
				"hire_date"				=> today(),
				"status"				=> 1,
				"job_title"				=> @$role->name,
				"department"			=> "Administrator",
				"supervisor_name"		=> auth()->user()->username,
			]);
			
			$user->roles()->attach($role);
	
			$user_info = CUser::where("username", $request->username)->with("roles", "profile_pic")->first();
			// ending tenant to access central DB
			tenancy()->end();
			
			User::create([
				"username" 				=> $user->username,
				"email" 				=> $user->email,
				"password" 				=> $user->password,
				"phone" 				=> $user->phone,
				"tenant_user_id" 		=> $user->id,
				"tenant_id" 			=> $req_name,
				"provision_account_id"	=> $provision_account->id,
				"role"					=> @$role->slug ? @$role->slug : "company_admin"
			]);
			DB::commit();
			
			$provision_detail = ProvisionAccount::Where("company_name", $request->company_name)
				->with("user", "billingContact", "subDetails")
				->first();
			
			$data = [
				"provision_detail"=> new ProvisionAccountResource($provision_detail),
				"user_info"=> new CUserResource($user_info)
			];
			return json_response(200, __("auth.provision_added"), $data);
			
		} catch (Exception $e) {
			DB::rollBack();
			
			$tenant = Tenant::find($req_name);
			if($tenant) {
				$tenant->domains()->delete();
				$tenant->delete();
			}
			return json_response(500, __('auth.db_error'));
		}
	}
	
	public function updateProvisionDetails($request, $provision_account)
	{
		$sub = Subscription::findByUUID($request->subscription_id);
		DB::beginTransaction();
		try {
			$provision_account->update([
				"dba_name"	=> @$request->dba_name,
				"address"		=> @$request->provision_address,
				"city"		=> @$request->provision_city,
				"phone"		=> @$request->provision_phone,
				"state"		=> @$request->provision_state,
				"zip"			=> @$request->provision_zip,
				"status"		=> @$request->provision_status,
			]);

			// Billing contact if updated 
			BillingContact::where("provision_account_id", $provision_account->id)
				->update([
					"fname"				=> @$request->billing_fname,
					"lname"				=> @$request->billing_lname,
					"title"				=> @$request->billing_title,
					"email"				=> @$request->billing_email,
					"contact_number"	=> @$request->billing_phone,
					"address"			=> @$request->billing_address,
					"city"				=> @$request->billing_city,
					"state"				=> @$request->billing_state,
					"zip"				=> @$request->billing_zip,
				]);

			// subscription Detail if Updated 
			SubscriptionDetail::where("provision_account_id", $provision_account->id)
				->update([
					"subscription_id" => $sub->id,
					"setup_fee" =>  @$request->setup_fee,
					"setup_fee_start_date" => date("Y-m-d H:i:s" , strtotime(@$request->setup_fee_start_date)),
					"recurring_billing_start_date" => date("Y-m-d H:i:s" , strtotime(@$request->recurring_billing_start_date)),
					"total" => $sub->price_per_license + @$request->setup_fee,
				]);

			SubscriptionHistory::where("provision_account_id", $provision_account->id)
				->update([
					"amount" => $sub->price_per_license + @$request->setup_fee,
				]);
			
			// Initializing tenant
			tenancy()->initialize(auth()->user()->tenant_id);
			
			$role = Role::where("slug", "company_admin")->first();
			$user = CUser::where("username", auth()->user()->username)->with("roles", "profile_pic")->first();
			$user->update([
				"fname"					=> @$request->fname,
				"lname"					=> @$request->lname,
				"address"				=> @$request->address,
				"phone"					=> @$request->phone,
				"city"					=> @$request->city,
				"state"					=> @$request->state,
				"zip_code"				=> @$request->zip,
				"job_title"				=> @$role->name,
				"department"			=> "Administrator",
				"supervisor_name" 		=> auth()->user()->username,
			]);

			// ending tenant to access central DB
			tenancy()->end();
			
			$outer_user = User::where("provision_account_id", $provision_account->id)->first();
			$outer_user->update([	
				"phone" 				=> $user->phone,
				"role"					=> @$role->slug ? @$role->slug : "company_admin"
			]);
			DB::commit();
			
			$provision_detail = ProvisionAccount::Where("id", auth()->user()->provision_account_id)
				->with("user", "billingContact", "subDetails")
				->first();

			// initializing tenant
			tenancy()->initialize(auth()->user()->tenant_id);

			$data = [
				"provision_detail"		=> new ProvisionAccountResource($provision_detail),
				"user"					=> $outer_user,
				"user_info"				=> $user
			];
			return json_response(200, "Provision account successfully updated.", $data);
			
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, "Records were not updated. Please try again.");
		}
	}
}
