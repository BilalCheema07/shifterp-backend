<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Location::factory()->count(10)->create();
    }
}
