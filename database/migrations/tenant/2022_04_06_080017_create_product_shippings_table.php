<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductShippingsTable extends Migration
{
	/**
	 * Run the migrations.
	 * 
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_shippings', function (Blueprint $table) {
			$table->id();
			$table->foreignId('product_id')->constrained()->onDelete('cascade');
			$table->integer('pallet_tie')->default(1);
			$table->string('kit_parent_cost', 20)->default("0.0000");
			$table->Integer('shelve_life')->default(0);
			$table->string('safety_stock', 20)->default("0.0000");
			$table->foreignId('safety_stock_unit')->nullable();
			$table->string('par_level', 20)->default("0.0000");
			$table->foreignId('par_level_unit')->nullable();
			$table->integer('minimum_blend_amount')->default(0);
			$table->boolean('is_global')->default(0);
			$table->boolean('is_kit_parent')->default(1);
			$table->boolean('is_high_risk')->default(0);
			$table->boolean('cost_item')->default(0);
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
		Schema::dropIfExists('product_shippings');
	}
}
