<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

class IProductCrudTest extends TestCase
{
	private $base_url = '/tenant/api/product/';

	public function testList()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url.'list');

		$response->assertStatus(200);
	}
	public function testDependencies()
	{
		$response = $this->withHeader('Authorization', 'Bearer ' . $GLOBALS['token'])->json('GET', $this->base_url.'dependencies');

		global $unit_uuid, $category_uuid;
		$unit_uuid = $response['data']['unit_types'][0]['units'][1]['uuid'];
		$category_uuid = $response['data']['categories'][0]['uuid'];

		$response->assertStatus(200);
	}
	
	public function testSave()
	{
		$response = $this->withHeader('Authorization', 'Bearer '.$GLOBALS['token'])
			->json('POST', $this->base_url.'save', 
		[
			'customer_id' => $GLOBALS['customer_uuid2'],
			'category_id' => $GLOBALS['category_uuid'],
			'name' => Str::random(8),
			'description' => Str::random(30),
			'internal_name' => Str::random(8),
			'internal_description' => Str::random(30),
			'barcode' => Str::random(13),
			'universal_product_code' => Str::random(5),//upc
			'status' => 1,
			
			'unit_of_stock' => $GLOBALS['unit_uuid'],
			'unit_of_order' => $GLOBALS['unit_uuid'],
			'unit_of_count' => $GLOBALS['unit_uuid'],
			'unit_of_package' => $GLOBALS['unit_uuid'],
			'unit_of_sell' => $GLOBALS['unit_uuid'],
			'unit_of_assembly' => $GLOBALS['unit_uuid'],
			'unit_of_purchase' => $GLOBALS['unit_uuid'],
			'variable_unit1' => $GLOBALS['unit_uuid'],
			'variable_unit2' => $GLOBALS['unit_uuid'],
			'convert_to_unit1' => $GLOBALS['unit_uuid'],
			'convert_to_unit2' => $GLOBALS['unit_uuid'],
			'convert_to_unit3' => $GLOBALS['unit_uuid'],
			'unit1_multiplier' => rand(60000, 99999) / 10000,
			'unit2_multiplier' => rand(60000, 99999) / 10000,
			'unit3_multiplier' => rand(60000, 99999) / 10000,
			'item_gross_weight' => rand(60000, 99999) / 10000,
			
			'pallet_tie' => rand(0,99999),
			'kit_parent_cost' => rand(600000, 999999) / 100000,
			'shelve_life' => rand(0,99999),
			'safety_stock' => rand(60000, 99999) / 10000,
			'safety_stock_unit' => $GLOBALS['unit_uuid'],
			'par_level' => rand(60000, 99999) / 10000,
			'par_level_unit' => $GLOBALS['unit_uuid'],
			'minimum_blend_amount' => rand(0,99999),
			'is_global' => rand(0,1),
			'is_kit_parent' => rand(0,1),
			'is_high_risk' => rand(0,1),
			'cost_item' => rand(0,1),
			
			// 'allergen_ids' => [
			// 	'a49946d00ef5472baa27d0c1f642b91d', 
			// 	'f7d52f3861f549a79e57e7b3dcf9d5d8', 
			// 	'0a62220d0d064c78a6cea8ee7666f5ed'
			// 	]
		]);
		// dd($response);
		global $product_uuid;
		$product_uuid = $response['data']['product']['uuid'];
		
		$response->assertStatus(200);
	}
		
	public function testUpdate()
	{
		$response = $this->withHeader('Authorization', 'Bearer '.$GLOBALS['token'])
			->json('POST', $this->base_url.'update/',
			[
			'uuid'						=> $GLOBALS['product_uuid'],	
			'customer_id' 				=> $GLOBALS['customer_uuid2'],
			'category_id' 				=> $GLOBALS['category_uuid'],
			'name' 						=> Str::random(8),
			'description' 				=> Str::random(30),
			'internal_name' 			=> Str::random(8),
			'internal_description' 		=> Str::random(30),
			'barcode' 					=> Str::random(13),
			'universal_product_code'	=> Str::random(5),
			'status' 					=> 1,
			
			'unit_of_stock' 			=> $GLOBALS['unit_uuid'],
			'unit_of_order' 			=> $GLOBALS['unit_uuid'],
			'unit_of_count' 			=> $GLOBALS['unit_uuid'],
			'unit_of_package'		 	=> $GLOBALS['unit_uuid'],
			'unit_of_sell' 				=> $GLOBALS['unit_uuid'],
			'unit_of_assembly' 			=> $GLOBALS['unit_uuid'],
			'unit_of_purchase' 			=> $GLOBALS['unit_uuid'],
			'variable_unit1' 			=> $GLOBALS['unit_uuid'],
			'variable_unit2' 			=> $GLOBALS['unit_uuid'],
			'convert_to_unit1' 			=> $GLOBALS['unit_uuid'],
			'convert_to_unit2' 			=> $GLOBALS['unit_uuid'],
			'convert_to_unit3' 			=> $GLOBALS['unit_uuid'],
			'unit1_multiplier' 			=> rand(60000, 99999) / 10000,
			'unit2_multiplier' 			=> rand(60000, 99999) / 10000,
			'unit3_multiplier' 			=> rand(60000, 99999) / 10000,
			'item_gross_weight' 		=> rand(60000, 99999) / 10000,
			
			'pallet_tie' 				=> rand(0,99999),
			'kit_parent_cost' 			=> rand(600000, 999999) / 100000,
			'shelve_life' 				=> rand(0,99999),
			'safety_stock' 				=> rand(60000, 99999) / 10000,
			'safety_stock_unit' 		=> $GLOBALS['unit_uuid'],
			'par_level' 				=> rand(60000, 99999) / 10000,
			'par_level_unit' 			=> $GLOBALS['unit_uuid'],
			'minimum_blend_amount' 		=> rand(0,99999),
			'is_global' 				=> rand(0,1),
			'is_kit_parent' 			=> rand(0,1),
			'is_high_risk' 				=> rand(0,1),
			'cost_item' 				=> rand(0,1),
				
				// 'allergen_ids' => [
				// 	'a49946d00ef5472baa27d0c1f642b91d', 
				// 	'f7d52f3861f549a79e57e7b3dcf9d5d8', 
				// 	'0a62220d0d064c78a6cea8ee7666f5ed'
				// 	]
			]);
			
		$response->assertStatus(200);
	}
			
	// public function testDelete()
	// {
	// 	$response = $this->withHeader('Authorization', 'Bearer '.$GLOBALS['token'])
	// 			->json('POST', $this->base_url.'delete',
	// 		[
	// 		'ids' => [
	// 			$GLOBALS['product_uuid']
	// 			]
	// 		]);
			
	// 	$response->assertStatus(200);
	// }
}
			