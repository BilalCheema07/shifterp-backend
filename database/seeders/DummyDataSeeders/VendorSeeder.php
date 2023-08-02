<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Vendor::factory()->count(5)->create();
    }
}
