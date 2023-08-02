<?php

namespace Tests\Feature;

use Tests\TestCase;
use illuminate\Support\Str;

class LocationTest extends TestCase
{
	private $base_url = '/tenant/api/location/';
	
	public function testLocationAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'name' => Str::random(8),
			'barcode' => Str::random(5),
			'custom_capacity' => rand(0, 999999),
			'remote_pick' =>  rand(0, 1),
			'allergen_pick' => rand(0, 1), 
			'tall_location' => rand(0, 1),
			'status' =>  rand(0, 1),
			
		]);
		
		global $Location_uuid;
		$Location_uuid = $response['data']['location']['uuid'];		
		$response->assertStatus(200);
	}
	
	public function testLocationList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'list', []);

		$response->assertStatus(200);
	}
	
	public function testLocationSingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get', [
			'uuid'		=> $GLOBALS['Location_uuid']
		]);
		$response->assertStatus(200);
	}

	
	public function testLocationUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'update', 
		[
			'uuid'=> $GLOBALS['Location_uuid'],
			'name' => Str::random(8),
			'barcode' => Str::random(5),
			'custom_capacity' => rand(0, 999999),
			'remote_pick' =>  rand(0, 1),
			'allergen_pick' => rand(0, 1), 
			'tall_location' => rand(0, 1),
			'status' =>  rand(0, 1),
		]);
		$response->assertStatus(200);
	}

	public function testLocationDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'delete', [
			'ids'		=> [$GLOBALS['Location_uuid']],
		]);
		$response->assertStatus(200);
	}
}
