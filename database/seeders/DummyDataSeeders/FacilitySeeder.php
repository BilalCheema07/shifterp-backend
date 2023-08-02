<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     *Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Facility::factory()->count(10)->create();
    }
}
