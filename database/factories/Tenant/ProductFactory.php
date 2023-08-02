<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
     /** Model */
     protected $model = \App\Models\Tenant\Product::class;
    /**
     * @return array
     */
    public function definition()
    {
        $customer = Customer::query()->InRandomOrder()->first();
        $category = Category::query()->InRandomOrder()->first();

        $product = [
            "customer_id" => $customer->id,
            "category_id" => $category->id,
            "name" => $this->faker->name(),
            "description" => $this->faker->sentence(),
            "internal_name" => $this->faker->name(),
            "internal_description" => $this->faker->sentence(),
            "barcode" => $this->faker->ean13(),
            "universal_product_code" => $this->faker->randomNumber(5, false),
            "status" => $this->faker->boolean()
        ];

        return $product;
    }
}
