<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use illuminate\Support\Str;


class ShipToTest extends TestCase
{
    private $base_url = '/tenant/api/ship-to/';
	
	public function testShipToAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'name' 					=> Str::random(8),
			'customer_id' 			=>  $GLOBALS['customer_uuid2'],
			'city' 					=>  Str::random(4),
			'state' 				=>   Str::random(5),
			'zip_code' 				=> rand(0, 99999), 
			'external_id' 			=> rand(0, 999999),
			'address1' 				=>  Str::random(15),
			'address2' 				=>  Str::random(15),
			'status' 				=> rand(0, 1),
			'primary_contact_name' 	=>  Str::random(8),
			'primary_contact_email' => "testshipTo@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
		]);

		global $ship_to_uuid, $ship_to_code;
		$ship_to_uuid = $response['data']['ship_to']['uuid'];

		$response->assertStatus(200);
	}
	
	public function testShipToList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'list', []);

		$response->assertStatus(200);
	}
	
	public function testShipToSingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get', [
			'uuid'		=> $GLOBALS['ship_to_uuid']
		]);
		$response->assertStatus(200);
	}

	
	public function testShipToUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'update', 
		[
			'uuid'=> $GLOBALS['ship_to_uuid'],

			'name' => Str::random(8),
			'customer_id' =>  $GLOBALS['customer_uuid2'],
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'external_id' => rand(0, 999999),
			'address1' =>  Str::random(15),
			'address2' =>  Str::random(15),
			'status' => rand(0, 1),
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testshipTo@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
			
		]);
		$response->assertStatus(200);
	}

    public function testShipToAdd2()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'name' => Str::random(8),
			'customer_id' =>  $GLOBALS['customer_uuid2'],
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'external_id' => rand(0, 999999),
			'address1' =>  Str::random(15),
			'address2' =>  Str::random(15),
			'status' => 1,
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testshipTo@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
		]);

		global $ship_to_uuid2;
		$ship_to_uuid2 = $response['data']['ship_to']['uuid'];
		
		$response->assertStatus(200);
	}

	public function testShipToDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'delete', [
			'id'		        => $GLOBALS['ship_to_uuid'],
            'ship_to_reassign'  => $GLOBALS['ship_to_uuid2'],
		]);
		$response->assertStatus(200);
	}

	public function testShipToMultiActionDeactivate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'multi-status-update', [
			'shipto_ids'		        => [$GLOBALS['ship_to_uuid2']],
            'action'  => 'de-active',
		]);
		$response->assertStatus(200);
	}
	public function testShipToMultiActionActive()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'multi-status-update', [
			'shipto_ids'		        => [$GLOBALS['ship_to_uuid2']],
            'action'  => 'active',
		]);
		$response->assertStatus(200);
	}
	public function testShipToMultiActionAddCustomer()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'multi-status-update', [
			'shipto_ids'		        => [$GLOBALS['ship_to_uuid2']],
            'action'  => 'add-customer',
			'customer_id' =>  $GLOBALS['customer_uuid2'],
		]);
		$response->assertStatus(200);
	}
}
