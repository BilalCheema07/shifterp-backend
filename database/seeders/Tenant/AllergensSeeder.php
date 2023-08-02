<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Allergen;
use Illuminate\Database\Seeder;

class AllergensSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$allergens = array (
			[
				'name'    => "Celery",
				'status'  => 1,
			],[
				'name'    => "Coconut",
				'status'  => 1,
			],[
				'name'    => "Halal",
				'status'  => 1,
			],[
				'name'    => "Kosher",
				'status'  => 1,
			],[
				'name'    => "Lupin",
				'status'  => 1,
			],[
				'name'    => "Mustard",
				'status'  => 1,
			],[
				'name'    => "NonGMO PV",
				'status'  => 1,
			],[
				'name'    => "Organic",
				'status'  => 1,
			],[
				'name'    => "Raw Eggs",
				'status'  => 1,
			],[
				'name'    => "Raw Milk",
				'status'  => 1,
			],[
				'name'    => "Raw Soy",
				'status'  => 1,
			],[
				'name'    => "Raw Treenuts",
				'status'  => 1,
			],[
				'name'    => "Raw wheat",
				'status'  => 1,
			],[
				'name'    => "Sesame",
				'status'  => 1,
			],[
				'name'    => "Sulfites",
				'status'  => 1,
			],
		);
		
		foreach ($allergens as $allergen){
			Allergen::create($allergen);
		}
	}
}
