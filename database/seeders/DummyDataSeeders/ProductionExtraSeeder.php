<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\ProductionExtra;
use Illuminate\Database\Seeder;

class ProductionExtraSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        ProductionExtra::factory()->count(10)->create();
    }
}
