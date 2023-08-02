<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\ExpenseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$expense_types = array(
			[
				'name'    => "Production Expense",
				'children' => array(
					[
						'name' => 'Selling & Administration',
					],[
						'name' => 'Plant & Operations',
					],[
						'name' => 'Financial Expense',
					],[
						'name' => 'Depreciation',
					],[
						'name' => 'Employee Expenses',
					]
				)
			], [
				'name'    => "Cold Storage Expense",
				'children' => array(
					[
						'name' => 'Selling & Administration',
					],[
						'name' => 'Rent',
					],[
						'name' => 'Blast Rent',
					],[
						'name' => 'Utilities',
					],[
						'name' => 'Operations',
					],[
						'name' => 'Claims',
					],[
						'name' => 'Financial',
					],[
						'name' => 'Depreciation',
					]
				)
			]
		);
		foreach ($expense_types as $expense_type) {
				$perm1 = [
					'name' => $expense_type['name'],
					'parent_id' => 0
				];
				$exp_type = ExpenseType::create($perm1);
		
				if ($exp_type) {
					foreach ($expense_type['children'] as $child) {
						$child['parent_id'] = $exp_type->id;
						ExpenseType::create($child);
					}
				}
		}
	}
}
