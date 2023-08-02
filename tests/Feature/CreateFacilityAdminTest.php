<?php

namespace Tests\Feature;

use Tests\TestCase;
use illuminate\Support\Str;

class CreateFacilityAdminTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	public function testGetRolesAndPermissions()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', '/tenant/api/all-roles-permissions');

		global $facility_admin_role_uuid;
		$facility_admin_role_uuid = $response['data']['roles'][1]['uuid'];
		$response->assertStatus(200);
	}

	public function testCreateFacilityAdmin()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', '/tenant/api/user/save', [
			'role_id'		=> $GLOBALS['facility_admin_role_uuid'],
			'fname'			=> Str::random(9),
			'lname'			=> Str::random(7),
			'email'			=> Str::random(5)."@mail.com",
			'phone'			=> +923001234567,
			'username'		=> Str::random(8),
			'address'		=> Str::random(20),
			'city'			=> Str::random(10),
			'state'			=> Str::random(10),
			'zip_code'		=> Str::random(5),
			'status'		=> rand(0,1),
			'facilities'	=> array(),
			'permission_ids'=> array(),
		]);

		global $facility_admin_uuid;
		$facility_admin_uuid = $response['data']['user']['uuid'];

		$response->assertStatus(200);
	}
}
