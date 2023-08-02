<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$perm_arr = array(
			[
				'name' => 'Order / Schedule',
				'slug' => 'order',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Inventory',
				'slug' => 'inventory',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					],[
						'name' => 'Hold',
						'slug' => 'hold',
					],[
						'name' => 'Move',
						'slug' => 'move',
					],[
						'name' => 'Add Adjustment',
						'slug' => 'add_adjustment',
					]
				)
			],
			[
				'name' => 'Kits',
				'slug' => 'kits',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Locations',
				'slug' => 'locations',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Carriers',
				'slug' => 'carriers',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Pricing',
				'slug' => 'pricing',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Admin Reports',
				'slug' => 'admin_reports',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'EDI',
				'slug' => 'edi',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Customers',
				'slug' => 'customers',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Products',
				'slug' => 'products',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			],
			[
				'name' => 'Labor',
				'slug' => 'labor',
				'children' => array(
					[
						'name' => 'View',
						'slug' => 'view',
					],[
						'name' => 'Edit',
						'slug' => 'edit',
					]
				)
			]
		);

		foreach ($perm_arr as $perm) {
			$perm1 = [
				'name' => $perm['name'],
				'slug' => $perm['slug'],
				'parent_id' => 0
			];
			$permission = Permission::create($perm1);

			if ($permission) {
				foreach ($perm['children'] as $child) {
					$child['parent_id'] = $permission->id;
					Permission::create($child);
				}
			}
		}
	}
}
