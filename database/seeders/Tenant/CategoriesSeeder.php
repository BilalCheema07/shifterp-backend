<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
	/**
	* Run the database seeds.
	*
	* @return void
	*/
	public function run()
	{
		$categories = array(
			[
				'name'    => "Finished Good",
				'status'  => 1,
			], [
				'name'    => "Raw Material",
				'status'  => 1,
			], [
				'name'    => "Storage",
				'status'  => 1,
			], [
				'name'    => "Storage Dry",
				'status'  => 1,
			], [
				'name'    => "Box",
				'status'  => 1,
			], [
				'name'    => "Cup",
				'status'  => 1,
			], [
				'name'    => "IM Finished/Blend",
				'status'  => 1,
			], [ 
				'name'    => "Label",
				'status'  => 1,
			], [ 
				'name'    => "LID",
				'status'  => 1,
			], [ 
				'name'    => "Liner",
				'status'  => 1,
			], [ 
				'name'    => "Pallet",
				'status'  => 1,
			], [ 
				'name'    => "Poly",
				'status'  => 1,
			], [
				'name'    => "SlipSheet",
				'status'  => 1,
			], [
				'name'    => "Tote",
				'status'  => 1,
			], [
				'name'    => "Tray",
				'status'  => 1,
			],
		);

		foreach ($categories as $category) {
			Category::create($category);
		}
	}
}
