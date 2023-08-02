<?php

namespace Tests\Feature;

use Tests\TestCase;
use illuminate\Support\Str;

class FacilitiesTest extends TestCase
{
	private $base_url = '/tenant/api/facility/';

	public function testFacilityAdminList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url. 'admins');
		$response->assertStatus(200);
	}

	public function testFacilityAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url .'save', [
			'admin_id' => $GLOBALS['facility_admin_uuid'],
			'name' => Str::random(8),
			'office_phone' => +923001234567,
			'address' =>  Str::random(30),
			'city' =>  Str::random(8),
			'state' =>  Str::random(8),
			'zip_code' => rand(0,999999),
		]);

		global $fac_uuid;
		$fac_uuid = $response['data']['facility']['uuid'];

		$response->assertStatus(200);
	}

	public function testFacilityList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url. 'list');

		$response->assertStatus(200);
	}

	public function testFacilitySingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get', [
			'uuid' => $GLOBALS['fac_uuid']
		]);
		$response->assertStatus(200);
	}
	
	public function testUserFacilities()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get-user-facilities', [
			'user_id' => $GLOBALS['user_uuid']
		]);

		$response->assertStatus(200);
	}
	
	public function testMakeActiveFacility()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'make-active-facility',[
			'facility_id' => $GLOBALS['fac_uuid'],
		]);

		$response->assertStatus(200);
	}
	
	public function testAddFacilityInUser()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson($this->base_url. 'add-facility-in-user', 
		[
			'user_ids' => [$GLOBALS['user_uuid']],
			'facility_ids' => [$GLOBALS['fac_uuid']],
			'type' => 'single'
		]);
		
		$response->assertStatus(200);
	}

	public function testFacilityUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson($this->base_url. 'update', 
		[
			'uuid' => $GLOBALS['fac_uuid'],
			'admin_id' => $GLOBALS['facility_admin_uuid'],
			'name' =>  Str::random(8),
			'office_phone' => +923001234567 ,
			'address' =>  Str::random(30),
			'city' =>  Str::random(14),
			'state' =>  Str::random(8),
			'zip_code' => rand(0,999999),
			'status' => rand(0, 1),
		]);
		
		$response->assertStatus(200);
	}
	
	public function testFacilitySearch()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson($this->base_url. 'list', 
		[
			'name' => 'facility',
		]);
		$response->assertStatus(200);
	}
	
	public function testFacilityRemoveFromProfile()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson($this->base_url. 'remove-facilities-from-profile', 
		[
			'facility_ids' => [$GLOBALS['fac_uuid']]
		]);
		$response->assertStatus(200);
	}
	
	public function testFacilityDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->postjson($this->base_url. 'delete', [
			'uuid' => $GLOBALS['fac_uuid']
		]);
		$response->assertStatus(200);
	}
}
