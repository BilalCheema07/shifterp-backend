<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        Expense::factory()->count(10)->create();
    }
}
