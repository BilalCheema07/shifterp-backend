<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

class RevenueTest extends TestCase
{
	private $base_url = "/tenant/api/accounting/";
	

	public function testAccountingDependency()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "dependencies", [
			"name" => "revenue", 
		]);

		global $revenue_type, $shift;
		$revenue_type = $response["data"]["revenue_type"][0]["uuid"];
		$shift = $response["data"]["shift"][0]["uuid"];

		$response->assertStatus(200);
	} 

	//Add New Revenue Test
	public function testAddRevenue()
	{
		$revenues = [
			"revenue_type_id" => $GLOBALS["revenue_type"],
			"shift_id" => $GLOBALS["shift"],
			"amount" => rand(000, 999),
			"date" => date("Y-m-d"),
			"notes" => Str::random(5)	
		];
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "add-new-revenue", 
		[
		   "revenues" => [$revenues]
		]);
		$response->assertStatus(200);
	}
	
	//Listing Revenue Test
	public function testListRevenue()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "list-revenue");

		global $revenue_uuid;
		$revenue_uuid = $response["data"]["revenues"][0]["uuid"];
		$response->assertStatus(200);
	}
	
	//Update Revenue Test
	public function testUpdateRevenue()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "update-revenue", 
		[
			"uuid" => $GLOBALS["revenue_uuid"],
			"revenue_type_id" => $GLOBALS["revenue_type"],
			"shift_id" => $GLOBALS["shift"],
			"amount" => rand(000, 999),
			"date" => date("Y-m-d"),
			"notes" => Str::random(5)
		]);
		$response->assertStatus(200);
	}

	//Delete Revenue Test
	public function testRevenueDelete()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "delete-revenue", [
			"revenue_uuid"		=> [$GLOBALS["revenue_uuid"]],
		]);
		$response->assertStatus(200);
	}
}
