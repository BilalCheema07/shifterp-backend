<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUnitsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_units', function (Blueprint $table) {
			$table->id();
			$table->foreignId('product_id')->constrained()->onDelete('cascade');
			
			$table->foreignId('unit_of_stock')->nullable()->constrained('units');
			$table->foreignId('unit_of_order')->nullable()->constrained('units');
			$table->foreignId('unit_of_purchase')->nullable();
			$table->foreignId('unit_of_count')->nullable()->constrained('units');
			$table->foreignId('unit_of_package')->nullable()->constrained('units');
			$table->foreignId('unit_of_sell')->nullable()->constrained('units');
			$table->foreignId('unit_of_assembly')->nullable();

			$table->foreignId('variable_unit1')->nullable();
			$table->foreignId('variable_unit2')->nullable();
			$table->foreignId('convert_to_unit1')->nullable()->constrained('units');
			$table->foreignId('convert_to_unit2')->nullable()->constrained('units');
			$table->foreignId('convert_to_unit3')->nullable();

			$table->string('unit1_multiplier', 20)->default(0.0000);
			$table->string('unit2_multiplier', 20)->default(0.0000);
			$table->string('unit3_multiplier', 20)->default(0.0000);
			$table->string('item_gross_weight', 20)->default(0.0000);
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
		Schema::dropIfExists('product_units');
	}
}
