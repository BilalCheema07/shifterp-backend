<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\{Product, ProductShipping, Allergen, Unit, ProductUnit};
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeder.
     *
     * @return void
     */
    public function run()
    {
        foreach (Product::factory()->count(10)->create() as $product) {
            
            //Product Shipping Detail
            ProductShipping::create([
				"product_id" => $product->id,
                "is_global" => rand(0,1),
                "is_kit_parent"  => rand(0,1),
                "is_high_risk" => rand(0,1),
                "cost_item" => rand(0, 1),
				"pallet_tie" => rand(000, 999),
				"kit_parent_cost" => rand(000, 999),
				"shelve_life" => rand(000, 999),
				"safety_stock" => rand(000, 999),
				"par_level" => rand(000, 999),
				"minimum_blend_amount" => rand(000, 999),
				"safety_stock_unit" => $this->getUnitId(),
				"par_level_unit" => $this->getUnitId(),
			]);

            //Product Units Detail
            ProductUnit::create([
				"product_id" => $product->id,
				"unit_of_stock" => $this->getUnitId(),
				"unit_of_order" => $this->getUnitId(),
				"unit_of_count" => $this->getUnitId(),
				"unit_of_package" => $this->getUnitId(),
				"unit_of_sell" => $this->getUnitId(),
				"unit_of_assembly" => $this->getUnitId(),
				"unit_of_purchase" => $this->getUnitId(),
				"variable_unit1" => $this->getUnitId(),
				"variable_unit2" => $this->getUnitId(),
				"convert_to_unit1" => $this->getUnitId(),
				"convert_to_unit2" => $this->getUnitId(),
				"convert_to_unit3" => $this->getUnitId(),
                "unit1_multiplier" => rand(000, 999),
                "unit2_multiplier" => rand(000, 999),
                "unit3_multiplier" => rand(000, 999),
                "item_gross_weight" => rand(000, 999),
			]);

            //Product Allergens Detail 
            $allergen_ids = Allergen::query()->inRandomOrder()->pluck("id");
            $product->allergens()->sync($allergen_ids);
        }
    }

    private function getUnitId()
	{
        return Unit::query()->inRandomOrder()->first()->id;
	}
}
