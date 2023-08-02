<?php

namespace Tests\Feature;

use Tests\TestCase;
use illuminate\Support\Str;

class CustomerTest extends TestCase
{
	private $base_url = '/tenant/api/customer/';
	
	public function testCustomerAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'primary_contact_name' => Str::random(8),
			'primary_contact_email' => Str::random(5).'@mail.com',
			'primary_contact_phone' => '+923001234567',
			'primary_contact_id' => 1,
			'name' => Str::random(8),
			'code' => Str::random(6),
			'city' => Str::random(8),
			'state' => Str::random(8),
			'zip_code' => rand(0, 999999), 
			'status' => rand(0, 1),
			'production_pick_logic' => Str::random(14),
			'shipping_pick_logic' => Str::random(14),
			'min_charge' => rand(0, 999999),
			'lot_number' => rand(0, 1),
			'lot_id1' => rand(0, 1),
			'lot_id2' => rand(0, 1),
			'receive_date' => rand(0, 1),
			'production_date' => rand(0, 1),
			'expiration_date' => rand(0, 1),
			'billed_date' => rand(0, 1),
			'show_unit_of_count' => rand(0, 1),
			'group_by_item' => rand(0, 1),
			'group_by_lot_number' => rand(0, 1),
			'group_by_lot_id1' => rand(0, 1),
			'group_by_lot_id2' => rand(0, 1),
		]);

		global $customer_id,$customer_uuid, $customer_code;
		$customer_id = $response['data']['customer']['id'];
		$customer_uuid = $response['data']['customer']['uuid'];
		$customer_code = $response['data']['customer']['code'];
		
		$response->assertStatus(200);
	}
	
	public function testCustomerList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'list', []);

		$response->assertStatus(200);
	}
	
	public function testCustomerSingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'show', [
			'uuid'		=> $GLOBALS['customer_uuid']
		]);
		$response->assertStatus(200);
	}
	
	public function testCustomerWithCode()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'customer-code', [
			'code'		=> $GLOBALS['customer_code']
		]);
		$response->assertStatus(200);
	}
	
	public function testCustomerUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'update', 
		[
			'uuid'					=> $GLOBALS['customer_uuid'],
			'primary_contact_name'	=> Str::random(8),
			'primary_contact_email' => Str::random(5).'@mail.com',
			'primary_contact_phone' => '+923001234567',
			'primary_contact_id'	=> 1,
			'name'					=> Str::random(8),
			'code'					=> $GLOBALS['customer_code'],
			'city'					=> Str::random(8),
			'state'					=> Str::random(8),
			'zip_code'				=> rand(0, 999999), 
			'status'				=> rand(0, 1),
			'production_pick_logic' => Str::random(8),
			'shipping_pick_logic'	=> Str::random(8),
			'min_charge'			=> rand(0, 999999),
			'lot_number'			=> rand(0, 1),
			'lot_id1'				=> rand(0, 1),
			'lot_id2'				=> rand(0, 1),
			'receive_date'			=> rand(0, 1),
			'production_date'		=> rand(0, 1),
			'expiration_date'		=> rand(0, 1),
			'billed_date'			=> rand(0, 1),
			'show_unit_of_count'	=> rand(0, 1),
			'group_by_item'			=> rand(0, 1),
			'group_by_lot_number'	=> rand(0, 1),
			'group_by_lot_id1'		=> rand(0, 1),
			'group_by_lot_id2'		=> rand(0, 1),
		]);
		$response->assertStatus(200);
	}

	public function testCustomerDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'delete', [
			'uuid'		=> $GLOBALS['customer_uuid'],
			'code'		=> $GLOBALS['customer_code']
		]);

		$response->assertStatus(200);
	}
	public function testCustomerAddGlobalUse()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'primary_contact_name' => Str::random(8),
			'primary_contact_email' => Str::random(5).'@mail.com',
			'primary_contact_phone' => '+923001234567',
			'primary_contact_id' => 1,
			'name' => Str::random(8),
			'code' => Str::random(6),
			'city' => Str::random(8),
			'state' => Str::random(8),
			'zip_code' => rand(0, 999999), 
			'status' => 1,
			'production_pick_logic' => Str::random(14),
			'shipping_pick_logic' => Str::random(14),
			'min_charge' => rand(0, 999999),
			'lot_number' => rand(0, 1),
			'lot_id1' => rand(0, 1),
			'lot_id2' => rand(0, 1),
			'receive_date' => rand(0, 1),
			'production_date' => rand(0, 1),
			'expiration_date' => rand(0, 1),
			'billed_date' => rand(0, 1),
			'show_unit_of_count' => rand(0, 1),
			'group_by_item' => rand(0, 1),
			'group_by_lot_number' => rand(0, 1),
			'group_by_lot_id1' => rand(0, 1),
			'group_by_lot_id2' => rand(0, 1),
		]);

		global $customer_uuid2;
		$customer_uuid2 = $response['data']['customer']['uuid'];		
		$response->assertStatus(200);
	}
	
}
