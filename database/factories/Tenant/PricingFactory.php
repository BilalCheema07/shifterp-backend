<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\{Category, ChargeType, Customer, PricingType, Product, Utype};
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingFactory extends Factory
{
    /** Model */
    protected $model = \App\Models\Tenant\Pricing::class;
    /**
    * @return array
    */
    public function definition()
    {
            $customer = Customer::query()->InRandomOrder()->first();
            $category = Category::query()->InRandomOrder()->first();
            $product = Product::query()->InRandomOrder()->first();
            
            $pricing_type = PricingType::query()->InRandomOrder()->first();
            $charge_type = ChargeType::query()->where('type', 'pricing')->InRandomOrder()->first();
            $unit_type = Utype::where('name', 'pricing')->first();
            $unit = $unit_type->units()->inRandomOrder()->first();

            return [
                "name" => $this->faker->name(),
                "customer_id" => $customer->id,
                "category_id" => $category->id,
                "product_id" => $product->id,
                "pricing_type_id" => $pricing_type->id,
                "charge_type_id" => $charge_type->id,
                "unit_id" => $unit->id,
                "lot_number" => $this->faker->randomNumber(5, false),
                "lot_id1" => $this->faker->randomNumber(5, false),
                "lot_id2" => $this->faker->randomNumber(5, false),
                "grace_period" => $this->faker->randomNumber(5, false),
                "price_per_unit" => $this->faker->randomNumber(5, false),
                "min_charge" => $this->faker->randomNumber(5, false),
                "status" => $this->faker->boolean(),  
            ];
    }
}
