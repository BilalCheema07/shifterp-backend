<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Facility;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
	/**
	 * Run the TenantDataSeeder.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach(Customer::factory()->count(5)->create() as $customer) {

			$facility_ids = Facility::inRandomOrder()->get('id');
				if(count($facility_ids) > 0){
					$customer->facilities()->attach($facility_ids);
				}
		}
		
	}
}
