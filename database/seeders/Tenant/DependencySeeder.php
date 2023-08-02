<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\{Dependency, DependencyType};

class DependencySeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$dependency_types = array(
			[
				"name"		=> "Allergens",
				"slug"		=> "allergens",
				"modules"	=> "",
				"children"	=> [
					["name" => "Celery"],
					["name" => "Coconut"],
					["name" => "Halal"],
					["name" => "Kosher"],
					["name" => "Lupin"],
					["name" => "Mustard"],
					["name" => "NonGMO PV"],
					["name" => "Organic"],
					["name" => "Raw Eggs"],
					["name" => "Raw Milk"],
					["name" => "Raw Soy"],
					["name" => "Raw Treenuts"],
					["name" => "Raw wheat"],
					["name" => "Sesame"],
					["name" => "Sulfites"],
				]
			],
			[
				"name"		=> "Categories",
				"slug"		=> "categories",
				"modules"	=> "",
				"children"	=> [
					["name" => "Finished Good"],
					["name" => "Raw Material"],
					["name" => "Storage"],
					["name" => "Storage Dry"],
					["name" => "Box"],
					["name" => "Cup"],
					["name" => "IM Finished/Blend"],
					["name" => "Label"],
					["name" => "LID"],
					["name" => "Liner"],
					["name" => "Pallet"],
					["name" => "Poly"],
					["name" => "SlipSheet"],
					["name" => "Tote"],
					["name" => "Tray"],
				]
			], [
				"name"		=> "Part Type",
				"slug"		=> "part_type",
				"modules"	=> "",
				"children"	=> [
					["name" => "Raw1"],
					["name" => "Raw2"],
					["name" => "Raw3"],
					["name" => "Raw4"],
					["name" => "Raw5"],
					["name" => "Raw6"],
					["name" => "Raw7"],
					["name" => "Raw8"],
					["name" => "Raw9"],
					["name" => "Box Primary"],
					["name" => "Box Secondary"],
					["name" => "Liner"],
					["name" => "Tray"],
					["name" => "Shrink"],
					["name" => "Other Primary"],
					["name" => "Other Secondary"],
					["name" => "Other Tertiary"],
					["name" => "Other Quaternary"],
					["name" => "Poly"],
					["name" => "Slipsheets"],
					["name" => "Intermediate Raw"],
					["name" => "Raw10"],
					["name" => "Raw11"],
					["name" => "Raw12"],
					["name" => "Raw13"],
					["name" => "Raw14"],
					["name" => "Raw15"],
					["name" => "Raw16"],
					["name" => "Raw17"],
					["name" => "Raw18"],
					["name" => "Raw19"],
					["name" => "Raw20"]
				]
			], [
				"name"		=> "Stack Type",
				"slug"		=> "stack_type",
				"modules"	=> "",
				"children"	=> [
					["name" => "Pallets"],
					["name" => "Slip Sheet"],
				]
			], [
				"name"		=> "Charge Type",
				"slug"		=> "charge_type",
				"modules"	=> "",
				"children"	=> [
					["name" => "Prepaid"],
					["name" => "3rd Party"],
					["name" => "Collect"],
				]
			], [
				"name"		=> "Driver",
				"slug"		=> "driver",
				"modules"	=> "",
				"children"	=> [
					["name" => "Quinton Cornia"],
					["name" => "Andrew Piper"],
					["name" => "Andrew Coram"],
					["name" => "Thomas Hudson"],
					["name" => "David Tovar"],
					["name" => "Ralph Castro"],
					["name" => "Timothy Zabala"],
				]
			]
		);
		// , [
		// 	"name"		=> "Driver",
		// 	"slug"		=> "driver",
		// 	"modules"	=> "",
		// 	"children"	=> 
		// ]
        foreach ($dependency_types as $type) {
			$dependency_created = DependencyType::create([
				"name" => $type["name"],
				"slug" => $type["slug"],
				"modules" => $type["modules"],
			]);
			if (count(@$type["children"])) {
				foreach ($type["children"] as $child) {
					Dependency::create([
						"type_id" => $dependency_created->id,"name" => $child["name"],
						"module" => @$child["module"] ?? "",
					]);
				}
			}
		}
	}
}
