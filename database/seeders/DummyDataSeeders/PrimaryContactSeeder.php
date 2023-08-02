<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\PrimaryContact;
use Illuminate\Database\Seeder;

class PrimaryContactSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        PrimaryContact::factory()->count(10)->create();
    }
}
