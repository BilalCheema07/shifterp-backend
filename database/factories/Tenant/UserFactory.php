<?php

namespace Database\Factories\Tenant;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /** Model */
    protected $model = \App\Models\Tenant\User::class;
    /**
     * @return array
     */
    public function definition()
    {
        $password = strtolower(Str::random(8));
		$tenant_id = "Testing_Company";
		
		
		tenancy()->initialize($tenant_id);
		
		$user = [
            'fname' => $this->faker->name,
            'lname' => $this->faker->name,
            'username' => $this->faker->unique()->name(),
            'password' => Hash::make(trim(12345678)),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city,
            'remember_token' => Str::random(10),
            'zip_code' => $this->faker->postcode(),
            'state' => $this->faker->state(),
            'birth_date' => $this->faker->dateTimeBetween('1990-01-01', '2023-12-31')
            ->format('Y/m/d'),
            'hire_date' => today(),
            'release_date' => today(),
            'status' => 1,
            'job_title' => 'Admin',
            'department' => 'Administrator',
            'supervisor_name' => 'Super Admin',
    ];
        return $user;
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

}
