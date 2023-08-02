<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\RevenueType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RevenueTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$revenue_types = array(
			[
				'name'    => "Revenue",
			], [
				'name'    => "Expense",
			], [
				'name'    => "Credit",
			]
		);

		foreach ($revenue_types as $revenue_type) {
			RevenueType::create($revenue_type);
		}
	}
}
