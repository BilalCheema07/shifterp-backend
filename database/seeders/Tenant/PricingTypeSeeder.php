<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\PricingType;
use Illuminate\Database\Seeder;

class PricingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pricing_types = array(
			[
				'name'    => "Split Month",
			], [
				'name'    => "Weekly",
			], [
				'name'    => "30-Day Anniversary",
			], [
				'name'    => "182 - Day Anniversary",
			],
		);

        foreach ($pricing_types as $pricing_type) {
			PricingType::create($pricing_type);
		}
    }
}
 
