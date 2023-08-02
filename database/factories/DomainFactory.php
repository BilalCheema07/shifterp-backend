<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        $data = $this->faker->word;
        return [
            'name' => $data,
            'display_name' => $this->faker->domainWord(),
            'domain' => $data.'Localhost',
            'tenant_id' => $data,
        ];
    }
}
