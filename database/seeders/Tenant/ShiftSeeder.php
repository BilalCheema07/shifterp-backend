<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts = array(
			[
				'name'    => "All Shift(reserved for future use)",
			],
		);

        foreach ($shifts as $shift) {
			Shift::create($shift);
		}
    }
}
