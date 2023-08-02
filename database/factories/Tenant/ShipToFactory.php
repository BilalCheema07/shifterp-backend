<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Customer;
use App\Models\Tenant\PrimaryContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipToFactory extends Factory
{

     /** Model */
     protected $model = \App\Models\Tenant\ShipTo::class;
    /**
     * @return array
     */
    public function definition()
    {
        $primary_contact = PrimaryContact::query()->InRandomOrder()->first();
        $customer = Customer::query()->InRandomOrder()->first();

        return [
            "primary_contact_id" => $primary_contact->id,
            "customer_id" => $customer->id,
            "name" => $this->faker->name(),
            "external_id" => $this->faker->unique()->randomNumber(5, true),
            "address1"  => $this->faker->address(),
            "address2"  => $this->faker->address(),
            "city" => $this->faker->city(),
            "state" => $this->faker->state(),
            "zip_code" => $this->faker->postcode(),
            "status" => $this->faker->boolean(),
        ];
    }
}
