<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\RevenueType;
use App\Models\Tenant\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Revenue>
 */
class RevenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
         $revenue_type = RevenueType::query()->InRandomOrder()->first();
         $shift = Shift::query()->InRandomOrder()->first();

        return [
            "revenue_type_id" => $revenue_type->id,
            "shift_id" => $shift->id,
            "date" => $this->faker->date(),
            "amount" => $this->faker->randomNumber(3, true) ?? "",
            "notes" => $this->faker->sentence()
        ];
    }
}
