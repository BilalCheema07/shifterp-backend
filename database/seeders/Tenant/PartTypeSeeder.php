<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\PartType;
use Illuminate\Database\Seeder;

class PartTypeSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$part_types = array(
			[
				'name'    => "Raw1",
				'status'  => 1,
			], [
				'name'    => "Raw2",
				'status'  => 1,
			], [
				'name'    => "Raw3",
				'status'  => 1,
			], [
				'name'    => "Raw4",
				'status'  => 1,
			], [
				'name'    => "Raw5",
				'status'  => 1,
			], [
				'name'    => "Raw6",
				'status'  => 1,
			], [
				'name'    => "Raw7",
				'status'  => 1,
			], [
				'name'    => "Raw8",
				'status'  => 1,
			], [
				'name'    => "Raw9",
				'status'  => 1,
			], [
				'name'    => "Box Primary",
				'status'  => 1,
			], [
				'name'    => "Box Secondary",
				'status'  => 1,
			], [
				'name'    => "Liner",
				'status'  => 1,
			], [
				'name'    => "Tray",
				'status'  => 1,
			], [
				'name'    => "Shrink",
				'status'  => 1,
			], [
				'name'    => "Other Primary",
				'status'  => 1,
			], [
				'name'    => "Other Secondary",
				'status'  => 1,
			], [
				'name'    => "Other Tertiary",
				'status'  => 1,
			], [
				'name'    => "Other Quaternary",
				'status'  => 1,
			], [
				'name'    => "Poly",
				'status'  => 1,
			], [
				'name'    => "Slipsheets",
				'status'  => 1,
			], [
				'name'    => "Intermediate Raw",
				'status'  => 1,
			], [
				'name'    => "Raw10",
				'status'  => 1,
			], [
				'name'    => "Raw11",
				'status'  => 1,
			], [
				'name'    => "Raw12",
				'status'  => 1,
			], [
				'name'    => "Raw13",
				'status'  => 1,
			], [
				'name'    => "Raw14",
				'status'  => 1,
			], [
				'name'    => "Raw15",
				'status'  => 1,
			], [
				'name'    => "Raw16",
				'status'  => 1,
			], [
				'name'    => "Raw17",
				'status'  => 1,
			], [
				'name'    => "Raw18",
				'status'  => 1,
			], [
				'name'    => "Raw19",
				'status'  => 1,
			], [
				'name'    => "Raw20",
				'status'  => 1,
            ],
		);

		foreach ($part_types as $part_type) {
			PartType::create($part_type);
		}
	}
}
