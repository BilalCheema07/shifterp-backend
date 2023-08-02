<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Pricing;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Pricing::factory()->count(10)->create();
    }
}
