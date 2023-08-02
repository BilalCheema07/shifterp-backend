<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

class ProductionExtraTest extends TestCase
{
	private $base_url = "/tenant/api/accounting/";
	

    public function testAccountingDependency()
	{
		$response = $this->withHeader("Authorization", 'Bearer ' . $GLOBALS['token'])->json('POST', '/tenant/api/accounting/dependencies', [
            "name" => "production_extra", 
        ]);

		global $unit_id;
		$unit_id = $response["data"]["units"][0]["units"][1]["uuid"];
		$response->assertStatus(200);
	} 

    //Add New Production Extra Test
	public function testAddProductionExtra()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "add-new-production-extra", 
		[
			"name" => Str::random(8),
			"unit_id" => $GLOBALS["unit_id"],
			"amount" => Str::random(3),
			"direct_material" => rand(0, 1), 
			"status" =>  rand(0, 1),
			
		]);		
		$response->assertStatus(200);
	}
	
    //Listing Production Extra Test
	public function testListProductionExtra()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "list-production-extra", []);
        
        global $prod_extra_uuid;
        $prod_extra_uuid = $response["data"]["production_extras"][0]["uuid"];
		$response->assertStatus(200);
	}
	
    //Get Single Production Extra Test
	public function testSingleProductionExtra()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "get-single-production-extra", [
			"production_extra_uuid"		=> $GLOBALS["prod_extra_uuid"]
		]);
		$response->assertStatus(200);
	}

	//Update Production Extra Test
	public function testUpdateProductionExtra()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "update-production-extra", 
		[
			"uuid"=> $GLOBALS["prod_extra_uuid"],
			"name" => Str::random(8),
			"unit_id" => $GLOBALS["unit_id"],
			"amount" => Str::random(3),
			"direct_material" => rand(0, 1), 
			"status" =>  rand(0, 1),
		]);
		$response->assertStatus(200);
	}

    //Delete Production Extra
	public function testProductionExtraDelete()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "delete-production-extra", [
			"production_extra_uuid"		=> [$GLOBALS["prod_extra_uuid"]],
		]);
		$response->assertStatus(200);
	}
}
