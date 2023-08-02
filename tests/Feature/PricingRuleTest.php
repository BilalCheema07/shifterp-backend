<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

class PricingRuleTest extends TestCase
{
	private $base_url = "/tenant/api/accounting/";
	

    //Accounting dependency
    public function testAccountingDependency()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", "/tenant/api/accounting/dependencies", [
            "name" => "pricing", 
        ]);
        
		global $unit_id, $customer_id, $category_id, $charge_type_id, $pricing_type_id;
		$unit_id = $response["data"]["units"][0]["units"][1]["uuid"];
        $customer_id = $response["data"]["customer"][0]["uuid"];
        $category_id = $response["data"]["category"][0]["uuid"];
        $charge_type_id = $response["data"]["charge_types"][0]["uuid"];
        $pricing_type_id = $response["data"]["pricing_types"][0]["uuid"];

		$response->assertStatus(200);
	} 

    //Add Product for Pricing
    public function testAddProductForPricing()
	{
		$response = $this->withHeader('Authorization', 'Bearer '.$GLOBALS['token'])
			->json('POST', '/tenant/api/product/save', 
		[
			'customer_id' => $GLOBALS['customer_id'],
			'category_id' => $GLOBALS['category_id'],
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
		]);
	
		global $product_id;
		$product_id = $response['data']['product']['uuid'];
		
		$response->assertStatus(200);
	}

    //Add New Pricing Test
	public function testAddPricing()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "add-new-pricing", 
		[
			"name" => Str::random(8),
            "customer_id" => $GLOBALS["customer_id"],
            "category_id" => $GLOBALS["category_id"],
            "product_id" => $GLOBALS["product_id"],
			"unit_id" => $GLOBALS["unit_id"],
            "pricing_type_id" => $GLOBALS["pricing_type_id"],
            "charge_type_id" => $GLOBALS["charge_type_id"],
            "lot_number" => rand(0, 999),
            "lod_id1" => rand(0, 999),
            "lod_id2" => rand(0, 999), 
            "grace_period" => rand(0, 999), 
            "price_per_unit" => rand(0, 999), 
            "min_charge" => rand(0, 999), 
			"status" =>  rand(0, 1),
			
		]);		
		$response->assertStatus(200);
	}

    //Add Pricing Test For Reassign
    public function testPricingForReassign()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "add-new-pricing", 
		[
			"name" => Str::random(8),
            "customer_id" => $GLOBALS["customer_id"],
            "category_id" => $GLOBALS["category_id"],
            "product_id" => $GLOBALS["product_id"],
			"unit_id" => $GLOBALS["unit_id"],
            "pricing_type_id" => $GLOBALS["pricing_type_id"],
            "charge_type_id" => $GLOBALS["charge_type_id"],
            "lot_number" => rand(0, 999),
            "lod_id1" => rand(0, 999),
            "lod_id2" => rand(0, 999), 
            "grace_period" => rand(0, 999), 
            "price_per_unit" => rand(0, 999), 
            "min_charge" => rand(0, 999), 
			"status" =>  rand(0, 1),
			
		]);		

		$response->assertStatus(200);
	}
	
    //Listing Pricing Test
	public function testListPricing()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "list-pricing", []);
        
        global $pricing_uuid, $pricing_reassign_uuid;
        $pricing_uuid = $response["data"]["pricing"][0]["uuid"];
        $pricing_reassign_uuid = $response["data"]["pricing"][1]["uuid"];

		$response->assertStatus(200);
	}
	
    //Get Single Pricing Test
	public function testSinglePricing()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "get-single-pricing", [
			"pricing_uuid"		=> $GLOBALS["pricing_uuid"]
		]);
		$response->assertStatus(200);
	}

	//Update Pricing Test
	public function testUpdatePricing()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "update-pricing", 
		[
            "uuid" => $GLOBALS['pricing_uuid'],
			"name" => Str::random(8),
            "customer_id" => $GLOBALS["customer_id"],
            "category_id" => $GLOBALS["category_id"],
            "product_id" => $GLOBALS["product_id"],
			"unit_id" => $GLOBALS["unit_id"],
            "pricing_type_id" => $GLOBALS["pricing_type_id"],
            "charge_type_id" => $GLOBALS["charge_type_id"],
            "lot_number" => rand(0, 999),
            "lod_id1" => rand(0, 999),
            "lod_id2" => rand(0, 999), 
            "grace_period" => rand(0, 999), 
            "price_per_unit" => rand(0, 999), 
            "min_charge" => rand(0, 999), 
			"status" =>  rand(0, 1),
		]);		
		$response->assertStatus(200);
	}

    //Delete and reassign Pricing Rule Test
	public function testPricingDeleteAndReassign()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "delete-pricing", [
			'pricing_uuid' => $GLOBALS['pricing_uuid'],
            'pricing_reassign_uuid' => $GLOBALS['pricing_reassign_uuid']
		]);

		$response->assertStatus(200);
	}
}
