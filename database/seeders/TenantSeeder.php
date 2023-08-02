<?php

namespace Database\Seeders;

use Database\Seeders\Tenant\AllergensSeeder;
use Database\Seeders\Tenant\CategoriesSeeder;
use Database\Seeders\Tenant\ChargeTypeSeeder;
use Database\Seeders\Tenant\DriverSeeder;
use Database\Seeders\Tenant\PartTypeSeeder;
use Database\Seeders\Tenant\RolesSeeder;
use Database\Seeders\Tenant\StackTypeSeeder;
use Database\Seeders\Tenant\UnitsSeeder;
use Database\Seeders\Tenant\DependencySeeder;
use Database\Seeders\Tenant\ExpenseTypeSeeder;
use Database\Seeders\Tenant\PermissionsSeeder;
use Database\Seeders\Tenant\PricingTypeSeeder;
use Database\Seeders\Tenant\RevenueTypeSeeder;
use Database\Seeders\Tenant\ShiftSeeder;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(AllergensSeeder::class);
		$this->call(CategoriesSeeder::class);
		$this->call(PermissionsSeeder::class);
		$this->call(RolesSeeder::class);
		$this->call(UnitsSeeder::class);

		$this->call(ChargeTypeSeeder::class);
		$this->call(StackTypeSeeder::class);
		$this->call(DriverSeeder::class);

		$this->call(PartTypeSeeder::class);
		$this->call(DependencySeeder::class);
		$this->call(PricingTypeSeeder::class);
		$this->call(ExpenseTypeSeeder::class);
		$this->call(ShiftSeeder::class);
		$this->call(RevenueTypeSeeder::class);
		
		// $this->call(::class);
	}
}
