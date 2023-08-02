<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class KitFactory extends Factory
{

    /** Model */
    protected $model = \App\Models\Tenant\Kit::class;
    /**
     * @return array
     */
    public function definition()
    {
        $customer = Customer::query()->InRandomOrder()->first();
        return [
            "customer_id" => $customer->id,
            "name" => $this->faker->name(),
            "description" => $this->faker->sentence(),
        ];
    }
}
