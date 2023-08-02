<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\ChargeType;
use Illuminate\Database\Seeder;

class ChargeTypeSeeder extends Seeder
{
    public function run()
    {
        $charge_types = array(
            [
                "name" => "Prepaid",
                "type" => "shipping_order"
            ],
            [
                "name" => "3rd Party",
                "type" => "shipping_order"
            ],
            [
                "name" => "Collect",
                "type" => "shipping_order"
            ],


            [
                "name" => "Blast Freezing (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Blast Handling (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Container/ Railcar Handling (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Dry storage (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Frozen Storage 1 (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Frozen Storage 2 (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Handling 1 (RC) Handling Charge",
                "type" => "pricing"
            ],
            [
                "name" => "Handling 2 (RC) Handling Charge",
                "type" => "pricing"
            ],
            [
                "name" => "Handling Short Hold (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Handling Cross Dock (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Refrigerated Storage (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Room Freezing (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Storage Short Hold (RC)",
                "type" => "pricing"
            ],
            [
                "name" => "Recurring Storage (RE)",
                "type" => "pricing"
            ],
            [
                "name" => "Administrative Labor (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "After Hours Labor (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "Bill of Lading Charge (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "Cancellation (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "Export Document Fee (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "Picking (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "USDA Charges (SH)",
                "type" => "pricing"
            ],
            [
                "name" => "Warehouse Labor (SH)",
                "type" => "pricing"
            ],
        );

        foreach ($charge_types as $charge_type) {
			ChargeType::create($charge_type);
		}
    }
}
