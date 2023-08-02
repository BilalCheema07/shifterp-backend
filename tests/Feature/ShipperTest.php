<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use illuminate\Support\Str;


class ShipperTest extends TestCase
{
    private $base_url = '/tenant/api/shipper/';
	
	public function testShipperAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'shipper_name' => Str::random(8),
			'shipper_code' => Str::random(5),
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'external_id' => rand(0, 999999),
			'address' =>  Str::random(15),
			'status' => rand(0, 1),
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testshipper@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
		]);

		global $shipper_uuid, $shipper_code;
		$shipper_uuid = $response['data']['shipper']['uuid'];
		$shipper_code = $response['data']['shipper']['shipper_code'];
		
		$response->assertStatus(200);
	}
	
	public function testShipperList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'list', []);

		$response->assertStatus(200);
	}
	
	public function testShipperSingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get', [
			'uuid'		=> $GLOBALS['shipper_uuid']
		]);
		$response->assertStatus(200);
	}

	
	public function testShipperUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'update', 
		[
			'uuid'=> $GLOBALS['shipper_uuid'],

			'shipper_name' => Str::random(8),
			'shipper_code' => Str::random(5),
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'external_id' => rand(0, 999999),
			'address' =>  Str::random(15),
			'status' => rand(0, 1),
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "test@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
			
		]);
		$response->assertStatus(200);
	}

    public function testShipperAdd2()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'shipper_name' => Str::random(8),
			'shipper_code' => Str::random(5),
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'external_id' => rand(0, 999999),
			'address' =>  Str::random(15),
			'status' => 1,
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testshipper@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
		]);

		global $shipper_uuid2;
		$shipper_uuid2 = $response['data']['shipper']['uuid'];
		
		$response->assertStatus(200);
	}

	public function testShipperDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'delete', [
			'id'		        => $GLOBALS['shipper_uuid'],
            'shipper_reassign'  => $GLOBALS['shipper_uuid2'],
		]);
		$response->assertStatus(200);
	}
}
