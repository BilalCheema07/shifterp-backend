<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Revenue;
use Illuminate\Database\Seeder;

class RevenueSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Revenue::factory()->count(10)->create();
    }
}
