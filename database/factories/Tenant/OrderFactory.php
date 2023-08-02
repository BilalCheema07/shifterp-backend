<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{

    /** Model */
    protected $model = \App\Models\Tenant\Order::class;
 /**
     * @return array
     */
    public function definition()
    {
        $customer = Customer::query()->InRandomOrder()->first();
        
        return [
            "customer_id" => $customer->id,
			"type" => $this->faker->randomElement(['shipping', 'receiving', 'production', 'blend']),
			"date" => $this->faker->date(),
			"time" => $this->faker->time(),
			"po_number" => $this->faker->randomNumber(7, true) ?? "",
			"release_number" => $this->faker->randomNumber(7, true) ?? "",
			"po_notes" => $this->faker->sentence(),
            'schedule_id' => rand(000000, 999999),
			"notes" => $this->faker->sentence(),
			"updated_by" => "test",
			"status" => $this->faker->randomElement(['new', 'remote', 'ready', 'note', 'completed', 'not_enough'])
        ];
    }
}
