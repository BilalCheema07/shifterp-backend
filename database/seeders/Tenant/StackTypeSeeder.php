<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\StackType;
use Illuminate\Database\Seeder;

class StackTypeSeeder extends Seeder
{
    public function run()
    {
        $stack_types = array(
            [
                "name" => "Pallets"
            ],
            [
                "name" => "Slip Sheet"
            ],
        );

        foreach ($stack_types as $stack_type) {
			StackType::create($stack_type);
		}
    }
}
