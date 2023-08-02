<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class AAAProvisionAccountTest extends TestCase
{
	private $base_url = "/api/provision/";


	public function testAdminLogin()
	{
		$response = $this->postJson(
			"api/login",
			[
				"username" => "admin",
				"password" => "12345678"
			]
		);

		global $sadmin_token;
		$sadmin_token = $response["data"]["token"];

		$response->assertStatus(200);
	}

	public function testProvisionList()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["sadmin_token"])->json("GET", $this->base_url."list");

		$response->assertStatus(200);
	}

	public function testSubscriptionList()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["sadmin_token"])->json("GET", $this->base_url."subscription");

		global $subscription_id;
		$subscription_id = $response['data'][0]['uuid'];

		$response->assertStatus(200);
	}

	public function testCreateProvisionAcc()
	{
		$username = Str::random(11);
		$company_name = Str::random(8);
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["sadmin_token"])->json("POST", $this->base_url."store",
		[
			"company_name"      => ucfirst($company_name)." company.",
			"dba_name"          => strtolower($company_name)."_dba",
			"provision_address" => "M.M. Alam road , LHR",
			"provision_city"    => "Faisalabad",
			"provision_phone"   => "+923209500003",
			"provision_state"   => "punjab",
			"provision_zip"     => 38000,
			"provision_status"  => 1,

			"recurring_billing_start_date"  => "09/14/2022",
			"subscription_id"               => $GLOBALS["subscription_id"],
			"setup_fee"                     => 50,
			"setup_fee_start_date"          => "09/14/2022",

			"billing_fname"     => "ahmad",
			"billing_lname"     => "qureshi",
			"billing_title"     => "patner",
			"billing_email"     => "patner@gmail.com",
			"billing_phone"     => "+923209500003",
			"billing_address"   => "Samnabad",
			"billing_city"      => "Faisalabad",
			"billing_state"     => "punjab",
			"billing_zip"       => 38000,

			"fname"     => ucfirst(Str::random(8)),
			"lname"     => ucfirst(Str::random(11)),
			"username"  => $username,
			"phone"     => "+923209500003",
			"email"     => $username."@gmail.com",
			"address"   => "LHR",
			"city"      => "lhr",
			"zip_code"  => "38000",
			"state"     => "punjab"
		]);

		global $tenant_prov_uuid, $tenant_sub_uuid, $tenant_username;
		$tenant_username = $username;

		$tenant_sub_uuid = $response["data"]["provision_detail"]["Subscription_details"][0]["uuid"];
		$tenant_prov_uuid = $response["data"]["provision_detail"]["uuid"];

		$response->assertStatus(200);
	}

	public function testSubscriptionHistory()
	{
		$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["sadmin_token"])->json("POST", $this->base_url."sub-history", 
		[
			"provision_account_id" => $GLOBALS["tenant_prov_uuid"],
		]);

		$response->assertStatus(200);
	}

	// public function testSubscriptionStatusChange()
	// {
	// 	$response = $this->withHeader("Authorization", "Bearer " . $GLOBALS["sadmin_token"])->json("POST", $this->base_url."change_status", 
	// 	[
	// 		"provision_account_id"		=> $GLOBALS["tenant_prov_uuid"],
	// 		"status"					=> Arr::random(['in-processing', 'cancel'], 1)[0]
	// 	]);

	// 	$response->assertStatus(200);
	// }

}
