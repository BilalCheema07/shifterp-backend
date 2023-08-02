<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use illuminate\Support\Str;


class VendorTest extends TestCase
{
    private $base_url = '/tenant/api/vendor/';
	
	public function testVendorAdd()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[
			'company_name' => Str::random(8),
			'dba_name' => Str::random(5),
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'address' =>  Str::random(15),
			'status' => rand(0, 1),
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testvendor@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
			
		]);
        
		global $vendor_uuid, $company_name;
		$vendor_uuid = $response['data']['vendor']['uuid'];
		$company_name = $response['data']['vendor']['company_name'];
		
		$response->assertStatus(200);
	}
	
	public function testVendorList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'list', []);

		$response->assertStatus(200);
	}
	
	public function testVendorSingle()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'get', [
			'uuid'		=> $GLOBALS['vendor_uuid']
		]);
		$response->assertStatus(200);
	}

	
	public function testVendorUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'update', 
		[
			'uuid'=> $GLOBALS['vendor_uuid'],
            'company_name' => Str::random(8),
			'dba_name' => Str::random(5),
			'city' =>  Str::random(4),
			'state' =>   Str::random(5),
			'zip_code' => rand(0, 99999), 
			'address' =>  Str::random(15),
			'status' => rand(0, 1),
			'primary_contact_name' =>  Str::random(8),
			'primary_contact_email' => "testvendor@gmail.com",
			'primary_contact_phone' =>  rand(0, 9999999),
			
		]);
		$response->assertStatus(200);
	}

	public function testVendorDelete()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url.'delete', [
			'id'		=> $GLOBALS['vendor_uuid'],
		]);
		$response->assertStatus(200);
	}
}
