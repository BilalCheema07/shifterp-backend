<?php

namespace Database\Seeders;

use Database\Seeders\DummyDataSeeders\{
    UserSeeder,
    KitSeeder,
    OrderSeeder,
    VendorSeeder,
    ShipToSeeder,
    ShipperSeeder,
    ProductSeeder,
    LocationSeeder,
    FacilitySeeder,
    CustomerSeeder,
    ExpenseSeeder,
    PricingSeeder,
    PrimaryContactSeeder,
    ProductionExtraSeeder,
    ProvisionAccountSeeder,
    RevenueSeeder,
};

use Illuminate\Database\Seeder;

class TenantDataSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProvisionAccountSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FacilitySeeder::class);
        $this->call(PrimaryContactSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(ShipperSeeder::class);
        $this->call(ShipToSeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(KitSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(ProductionExtraSeeder::class);
        $this->call(PricingSeeder::class);
        $this->call(ExpenseSeeder::class);
        $this->call(RevenueSeeder::class);
    }
}
