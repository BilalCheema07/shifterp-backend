<?php

namespace Tests\Feature;

use Tests\TestCase;
use illuminate\Support\Str;

class OrderTest extends TestCase
{
	private $base_url_blend = '/tenant/api/blend-order/';
	private $base_url_production = '/tenant/api/production-order/';
	private $base_url_shipping = '/tenant/api/shipping-order/';
	private $base_url_receiving = '/tenant/api/receiving-order/';
	private $base_url_order = '/tenant/api/order/';
	
	public function testOrderDependency()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', '/tenant/api/order/dependencies');
	
		global $driver1, $driver2, $stack_type_id, $charge_type_id;
		$driver1 = $response['data']['drivers'][1]['uuid'];
		$driver2 = $response['data']['drivers'][2]['uuid'];
		$charge_type_id = $response['data']['charge_types'][0]['uuid'];
		$stack_type_id = $response['data']['stack_types'][0]['uuid'];
	
		$response->assertStatus(200);
	} 

	//Blend Order Test
	public function testBlendOrderAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url_blend.'add-blend-order', 
		[
            "customer_id" => $GLOBALS['customer_uuid2'],
			"kit_id" => $GLOBALS['kit_uuid'],
			"unit_id" => $GLOBALS['unit_uuid'],
			"driver1_id" => $GLOBALS['driver1'],
			"driver2_id" => $GLOBALS['driver2'],
			"date" => date('Y-m-d'),
			"time" => '13:00',
			"po_notes" => Str::random(8),
			"notes" => Str::random(5),
			"quantity" => rand(0, 999),
			"is_remote_pick" => rand(0, 1),
		]);
		$response->assertStatus(200);
	}

	//Production Order Test
	public function testProductionOrderAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url_production.'add-production-order', 
		[
            "customer_id" => $GLOBALS['customer_uuid2'],
			"kit_id" => $GLOBALS['kit_uuid'],
			"unit_id" => $GLOBALS['unit_uuid'],
			"driver1_id" => $GLOBALS['driver1'],
			"driver2_id" => $GLOBALS['driver2'],
			"date" => date('Y-m-d'),
			"time" => '13:00',
			"po_notes" => Str::random(8),
			"notes" => Str::random(5),
			"quantity" => rand(0, 999),
			"is_remote_pick" => rand(0, 1),
			"is_allergen_pick" => rand(0, 1),
		]);
			
		$response->assertStatus(200);
	}

   //Shipper ADD Test For UUID
	public function testShipperAddForOrder()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', '/tenant/api/shipper/save', 
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

		global $shipper_uuid;
		$shipper_uuid = $response['data']['shipper']['uuid'];
		
		$response->assertStatus(200);
	}
	
	//ShipTo ADD Test For UUID
	public function testShipToAddForOrder()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', '/tenant/api/ship-to/save', 
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

		global $ship_to_uuid;
		$ship_to_uuid = $response['data']['ship_to']['uuid'];

		$response->assertStatus(200);
	}

	//Shipping Order Test
	public function testShippingOrderAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url_shipping.'add-shipping-order', 
		[
			"shipper_id" => $GLOBALS['shipper_uuid'],
			"ship_to_id" => $GLOBALS['ship_to_uuid'],
			"stack_type_id" => $GLOBALS['stack_type_id'],
			"charge_type_id" => $GLOBALS['charge_type_id'],
            "customer_id" => $GLOBALS['customer_uuid2'],
			"driver1_id" => $GLOBALS['driver1'],
			"driver2_id" => $GLOBALS['driver2'],
			"date" => date('Y-m-d'),
			"time" => '13:00',
			"po_notes" => Str::random(8),
			"notes" => Str::random(5),
			"is_remote_pick" => rand(0, 1),
			"is_customer_called" => rand(0, 1),
			"is_allergen_pick" => rand(0, 1),
		]);
		
		$response->assertStatus(200);
	}

	//Receiving Order Test
	public function testReceivingOrderAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url_receiving. 'add-receiving-order', 
		[
			"shipper_id" => $GLOBALS['shipper_uuid'],
            "customer_id" => $GLOBALS['customer_uuid2'],
			"driver1_id" => $GLOBALS['driver1'],
			"driver2_id" => $GLOBALS['driver2'],
			"unit_id" => $GLOBALS['unit_uuid'],
			"received_from" => $GLOBALS['customer_uuid2'],
			"date" => date('Y-m-d'),
			"time" => '13:00',
			"po_notes" => Str::random(8),
			"notes" => Str::random(5),
			"quantity" => rand(0, 999),
		]);
		
		$response->assertStatus(200);
	}

	//Order listing Test
	public function testOrderListing()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url_order.'list');
		$response->assertStatus(200);
	}
}
