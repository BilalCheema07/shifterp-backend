<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\PrimaryContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{

    /** Model */
    protected $model = \App\Models\Tenant\Customer::class;
    /**
     * @return array
     */
    public function definition()
    {
        $primary_contact = PrimaryContact::query()->InRandomOrder()->first();
        return [
            "name" => $this->faker->name(),
            "primary_contact_id" => $primary_contact->id, 
            "code"  => $this->faker->unique()->regexify('[A-Z]{3}[0-4]{3}'),
            "city" => $this->faker->city(),
            "state" => $this->faker->state(),
            "zip_code" => $this->faker->postcode(),
            "production_pick_logic" => $this->faker->sentence(),
            "shipping_pick_logic" => $this->faker->sentence(),
            "min_charge" => $this->faker->randomNumber(5, false),
            "lot_number" => $this->faker->boolean(),
            "lot_id1" => $this->faker->boolean(),
            "lot_id2" => $this->faker->boolean(),
            "receive_date" => $this->faker->boolean(),
            "production_date" => $this->faker->boolean(),
            "expiration_date" => $this->faker->boolean(),
            "billed_date" => $this->faker->boolean(),
            "show_unit_of_count" => $this->faker->boolean(),
            "group_by_item" => $this->faker->boolean(),
            "group_by_lot_number" => $this->faker->boolean(),
            "group_by_lot_id1" => $this->faker->boolean(),
            "group_by_lot_id2" => $this->faker->boolean(),
            "status" => $this->faker->boolean(),
        ];
    }
}
