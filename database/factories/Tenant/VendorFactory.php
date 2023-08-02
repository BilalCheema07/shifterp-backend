<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\PrimaryContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
     /** Model */
     protected $model = \App\Models\Tenant\Vendor::class;
    /**
     * @return array
     */
    public function definition()
    {
        $primary_contact = PrimaryContact::query()->InRandomOrder()->first();

        return [
            "primary_contact_id" => $primary_contact->id,
            "company_name" => $this->faker->unique()->name(),
            "dba_name" => $this->faker->unique()->name(),
            "address"  => $this->faker->address(),
            "city" => $this->faker->city(),
            "state" => $this->faker->state(),
            "zip_code" => $this->faker->postcode(),
            "status" => $this->faker->boolean(),
        ];
    }
}
