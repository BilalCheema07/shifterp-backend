<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Tenant\Unit;
use App\Models\Tenant\PartType;
use App\Models\Tenant\Category;

class KitCrudTest extends TestCase
{
	private $base_url = '/tenant/api/kit/';
	

	public function testKitDdependency(){

		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url.'dependencies');

		global $part_type_uuid;
		$part_type_uuid = $response['data']['part_types'][0]['uuid'];
		$response->assertStatus(200);
	}

	public function testKitAdd(){

		 $products =[
			[
			"product_id" 	=> $GLOBALS['product_uuid'],
			"part_type_id"	=> $GLOBALS['part_type_uuid'],
			"unit_id"		=> $GLOBALS['unit_uuid'],
			"amount"		=> 300,
			]
		];

		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('POST', $this->base_url. 'save', 
		[

			"customer_id" 	=> $GLOBALS['customer_uuid2'],
			"name" 			=> Str::random(5),
			"description"	=> Str::random(10),
			"products" 		=> $products,
			
		]);
		
		global $kit_uuid;
		$kit_uuid = $response['data']['kit']['uuid'];		
		$response->assertStatus(200);
		
	}
	
	public function testList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url.'list');

		$response->assertStatus(200);
	}

	
}
