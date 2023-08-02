<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\{Kit, KitProduct, PartType, Product, Unit};
use Illuminate\Database\Seeder;

class KitSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        foreach(Kit::factory()->count(5)->create() as $kit){
            
			$product = Product::query()->inRandomOrder()->first();
			$part_type = PartType::query()->inRandomOrder()->first();
			$unit = Unit::query()->inRandomOrder()->first();

            // Add Product Kit
			KitProduct::create([
				'kit_id' => $kit->id,
				'product_id' => $product->id,
				'part_type_id' => $part_type->id,
				'unit_id' => $unit->id,
				'amount' => rand(000, 999),
			]);
        }
    }
}
