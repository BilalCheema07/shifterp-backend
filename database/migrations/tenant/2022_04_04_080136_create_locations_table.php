<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->string('name')->nullable();
			$table->string('barcode')->nullable();
			$table->integer('custom_capacity')->nullable();
			$table->boolean('is_remote_pick')->default(0);
			$table->boolean('is_allergen_pick')->default(0);
			$table->boolean('is_tall_location')->default(0);
			$table->tinyInteger('status')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('locations');
	}
}
