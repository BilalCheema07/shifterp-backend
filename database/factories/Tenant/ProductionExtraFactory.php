<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Utype;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionExtraFactory extends Factory
{
    /** Model */
    protected $model = \App\Models\Tenant\ProductionExtra::class;
    /**
     * @return array
     */
    public function definition()
    {
        $unit_type = Utype::where('name', 'production_extra')->first();
        $unit = $unit_type->units()->inRandomOrder()->first();
        
        return [
            "unit_id" => $unit->id,
            "name"  => $this->faker->unique()->name(),
            "amount" => $this->faker->randomNumber(),
            "direct_material" => $this->faker->boolean(),
            "status" => $this->faker->boolean(),
        ];
    }
}
