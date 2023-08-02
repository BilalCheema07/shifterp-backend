<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\Facility;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Role;
use App\Models\User;
use App\Models\Tenant\User as CUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSeeder extends Seeder
{
	/**
	 * Run the TenantDataSeeder.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach(CUser::factory()->count(10)->create() as $user) {
			$role = Role::query()->inRandomOrder()->first();

			tenancy()->end();
			
			$user1 = User::create([
				'username' => $user->username,
				'email' => $user->email,
				'password' => $user->password,
				'phone' => $user->phone,
				'tenant_user_id' => $user->id,
				'tenant_id' => "Testing_Company",
				'role' => $role->slug
			]);

			tenancy()->initialize($user1->tenant_id);
			$user->roles()->attach($role->id);

			$permission_valid_ids = Permission::inRandomOrder()->get('id');
				if(count($permission_valid_ids) > 0){
					$user->permissions()->attach($permission_valid_ids);
				}
		}
	}
}
