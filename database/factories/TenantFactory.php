<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;
use Faker\Generator as Faker;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;
    /**
     * Define the model's default state.
     * 
     * @return array
     */
    public function definition()
    {
        $data = [
            'id'=> $this->faker->unique()->word,
        ];
        return $data;
    }
}
