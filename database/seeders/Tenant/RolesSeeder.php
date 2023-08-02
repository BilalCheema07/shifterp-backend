<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$role_arr = array(
			[
				'name' => 'Company Administrator',
				'slug' => 'company_admin',
			],
			[
				'name' => 'Facility Admin',
				'slug' => 'facility_admin',
				
			],
			[
				'name' => 'Facility User',
				'slug' => 'facility_user',
				]
			);
			
			foreach ($role_arr as $role) {
				Role::create($role);
			}
		}
	}
	