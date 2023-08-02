<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Unit;
use App\Models\Tenant\Utype;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$units = array(
			[ "name" => "LBS" ],
			[ "name" => "EA" ],
			[ "name" => "CWT" ],
			[ "name" => "CS" ],
			[ "name" => "PLT" ],
			[ "name" => "TOTE" ],
			[ "name" => "BRL" ],
			[ "name" => "BAG" ],
			[ "name" => "PAIL" ],
			[ "name" => "WO" ],
			[ "name" => "roll" ],
			[ "name" => "liner" ],
			[ "name" => "each" ],
			[ "name" => "inch" ],
		);

		$utypes = array(
			[ "name" => "stock" ],
			[ "name" => "order" ],
			[ "name" => "purchase" ],
			[ "name" => "count" ],
			[ "name" => "package" ],
			[ "name" => "sell" ],
			[ "name" => "assembly" ],
			[ "name" => "variable_1" ],
			[ "name" => "variable_2" ],
			[ "name" => "convert_to_1" ],
			[ "name" => "convert_to_2" ],
			[ "name" => "convert_to_3" ],
			[ "name" => "safety_stock" ],
			[ "name" => "par_level" ],
			[ "name" => "kit" ],
			[ "name" => "production_extra" ],
			[ "name" => "pricing" ],
		);

		foreach ($units as $unit) {
			Unit::create($unit);
		}
		$unit_ids = Unit::all()->pluck("id");
		foreach ($utypes as $utype) {
			$this_utype = Utype::create($utype);
			if($utype[ "name" ] === "stock") {
				$this_utype->units()->sync([1, 2]);
			} else if($utype[ "name" ] === "production_extra") {
				$this_utype->units()->sync([1, 4, 10, 11, 12, 13, 14]);
			} else {
				$this_utype->units()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9]);
			}
		}
	}
}
