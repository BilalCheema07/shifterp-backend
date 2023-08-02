<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('customer_id')->constrained()->onDelete('cascade');
			$table->foreignId('category_id')->constrained()->onDelete('cascade');
			$table->string('name')->nullable();
			$table->string('description')->nullable();
			$table->string('internal_name')->nullable();
			$table->string('internal_description')->nullable();
			$table->string('barcode')->nullable();
			$table->string('universal_product_code')->nullable();
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
		Schema::dropIfExists('products');
	}
}
