<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Shipper;
use Illuminate\Database\Seeder;

class ShipperSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Shipper::factory()->count(10)->create();
    }
}
