<?php

namespace Database\Factories\Tenant;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{

      /** Model */
    protected $model = \App\Models\Tenant\Location::class;
    /**
     * @return array
     */
    public function definition()
    {
        return [
            "name"  => $this->faker->unique()->name(),
            "barcode" => $this->faker->ean13(),
            "custom_capacity" => $this->faker->randomNumber(),
            "is_remote_pick" => $this->faker->boolean(),
            "is_allergen_pick" => $this->faker->boolean(),
            "is_tall_location" => $this->faker->boolean(),
            "status" => $this->faker->boolean(),
        ];
    }
}
