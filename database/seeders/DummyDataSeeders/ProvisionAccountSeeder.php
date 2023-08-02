<?php

namespace Database\Seeders\DummyDataSeeders;

use Exception;
use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Tenant\Role;
use App\Models\Tenant\User as CUser;
use App\Models\{ProvisionAccount, BillingContact, Subscription, SubscriptionDetail, SubscriptionHistory, Tenant, User};


class ProvisionAccountSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        $req_name = str_replace(' ', '_', "Testing Company");
		try {
		$new_tenant = new Tenant();
			$new_tenant->id = $req_name;
			if($new_tenant->save()) {
				$new_tenant->domains()->create([
					'domain'        		=> $req_name.'.'."localhost",
					'dba_name'          	=> "testing_db",
					'display_name'  		=> "Testing Company",
				]);
			}
		} catch (Exception $e){
			$tenant = Tenant::find($req_name);
			if($tenant) {
				$tenant->domains()->delete();
				$tenant->delete();
			}
		}

        $provision_account = ProvisionAccount::create([
            "company_name" 	=> "Testing Company",
            "tenant_id" 	=> $req_name,
            "dba_name" 		=> "testing_db",
            "address" 		=> "Address",
            "city" 			=> "city",
            "phone" 		=> "123456789",
            "state" 		=> "state",
            "zip" 			=> 12345,
            "status" 		=> 1,
        ]);

        //Billing Contact
        BillingContact::create([
            "provision_account_id"	=> $provision_account->id,
            "fname"					=> "Testing",
            "lname"					=> "test",
            "title"					=> "Title",
            "email"					=> "test@gmail.com",
            "contact_number"		=> "1234546789",
            "address"				=> "Address",
            "city"					=> "City",
            "state"					=> "State",
            "zip"					=> 123456789
        ]);

        //Subscription Details
        $subscription = SubscriptionDetail::create([
            "provision_account_id"			=> $provision_account->id,
            "subscription_id"				=> Subscription::all()->random()->id,
            "recurring_billing_start_date"	=> Carbon::now(),
            "setup_fee" 					=> 100,
            "setup_fee_start_date"			=> Carbon::now(),
            "total" 						=> 200 + 100,
        ]);
        
        //Subscription History
        SubscriptionHistory::create([
            "provision_account_id"		=> $provision_account->id,
            "subscription_detail_id"	=> $subscription->id,
            "billed_date"				=> Carbon::now(),
            "status"					=> 1,
            "amount"					=> 200 + 100
        ]);
        
        //Initializing tenant
        tenancy()->initialize($new_tenant);
        
        $role = Role::where("slug", "company_admin")->first();
        
        $user = CUser::create([
            "fname"					=> "test",
            "lname"					=> "dummy",
            "username"				=> "test",
            "password"				=> Hash::make(trim(12345678)),
            "phone"					=> "124123414",
            "email"					=> "test@gmail.com",
            "hire_date"				=> today(),
            "status"				=> 1,
            "job_title"				=> @$role->name,
            "department"			=> "Administrator",
            "supervisor_name"		=> "test",
        ]);
        
        $user->roles()->attach($role);

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
    }
}
