<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Support\Str;

class ExpenseTest extends TestCase
{
    private $base_url = "/tenant/api/accounting/";
	

	public function testAccountingDependency()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "dependencies", [
			"name" => "expense", 
		]);
    
		global $expense_type, $expense_child;
		$expense_type = $response["data"]["expense_type"][0]["uuid"];
        $expense_child = $response["data"]["expense_type"][0]["children"][0]["uuid"];

		$response->assertStatus(200);
	} 

	//Add New Expense Test
	public function testAddExpense()
	{
		$data = [[
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
        ],
        [
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
		],
        [
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
		]];

		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "add-new-expense", 
		[
            "expense_type_id" => $GLOBALS["expense_type"],
			"date" => date("Y-m-d"),
			"data" => $data
		]);

		$response->assertStatus(200);
	}
	
	//Listing Expense Test
	public function testListExpense()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "list-expense", []);

		global $expense_uuid;
		$expense_uuid = $response["data"]["expenses"][0]["uuid"];
		$response->assertStatus(200);
	}
	
	//Update Expense Test
	public function testUpdateExpense()
	{
        $data = [[
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
        ],
        [
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
		],
        [
			"type_id" => $GLOBALS["expense_child"],
            "amount" => rand(000, 999),
		]];
		
        $response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "update-expense", 
		[
            "uuid" => $GLOBALS["expense_uuid"],
            "expense_type_id" => $GLOBALS["expense_type"],
			"date" => date("Y-m-d"),
			"data" => $data
		]);
		$response->assertStatus(200);
	}

	//Delete Expense Test
	public function testExpenseDelete()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["token"])->json("POST", $this->base_url. "delete-expense", [
			"expense_uuid"		=> [$GLOBALS["expense_uuid"]],
		]);
		$response->assertStatus(200);
	}
}
