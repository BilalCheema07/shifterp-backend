<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
        /** Model */
    protected $model = \App\Models\Tenant\Facility::class;
    /**
     * @return array
     */
    public function definition()
    {
        $user = User::facilityAdmins()->inRandomOrder()->first();
        $facility = [
            'name' => $this->faker->unique()->name,
            'admin_id' => $user->id,
            'office_phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city,
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'status'   => $this->faker->boolean()  
        ];
        
        return $facility;
    }
}
