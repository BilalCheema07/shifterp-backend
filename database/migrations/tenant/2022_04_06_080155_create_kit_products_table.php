<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kit_products', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('kit_id')->constrained()->onDelete('cascade');
			$table->foreignId('product_id')->constrained()->onDelete('cascade');
			$table->foreignId('part_type_id')->constrained()->onDelete('cascade');
			$table->unsignedBigInteger('parent_id')->default(0);
			$table->unsignedBigInteger('priority')->default(0);
			$table->integer('amount');
			$table->string('unit_id')->default('1');
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
		Schema::dropIfExists('kit_products');
	}
}
