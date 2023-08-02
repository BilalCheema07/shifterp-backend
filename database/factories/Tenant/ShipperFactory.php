<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\PrimaryContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipperFactory extends Factory
{
     /** Model */
     protected $model = \App\Models\Tenant\Shipper::class;
    /**
     * @return array
     */
    public function definition()
    {
        $primary_contact = PrimaryContact::query()->InRandomOrder()->first();

        return [
            "primary_contact_id" => $primary_contact->id,
            "shipper_name" => $this->faker->name(),
            "shipper_code" => $this->faker->unique()->regexify('[A-Z]{3}[0-4]{3}'),
            "external_id" => $this->faker->unique()->randomNumber(5, true),
            "address"  => $this->faker->address(),
            "city" => $this->faker->city(),
            "state" => $this->faker->state(),
            "zip_code" => $this->faker->postcode(),
            "status" => $this->faker->boolean(),
        ];
    }
}
