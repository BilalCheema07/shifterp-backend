<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\ShipTo;
use Illuminate\Database\Seeder;

class ShipToSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        ShipTo::factory()->count(5)->create();
    }
}
