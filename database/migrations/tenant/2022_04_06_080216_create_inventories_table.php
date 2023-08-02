<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventories', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('product_id')->constrained()->onDelete('cascade');
			$table->foreignId('location_id')->default(0)->constrained();
			$table->foreignId('customer_id')->constrained()->onDelete('cascade');
			$table->text('description')->nullable();
			$table->integer('lot_id')->nullable();
			$table->integer('lot_id1')->nullable();
			$table->integer('lot_id2')->nullable();
			$table->integer('pal_id')->default(0);
			$table->string('total1')->nullable();
			$table->string('total2')->nullable();
			$table->string('picked1')->nullable();
			$table->string('picked2')->nullable();
			$table->string('avail1')->nullable();
			$table->string('avail2')->nullable();
			$table->integer('pallet_number')->default(0);
			$table->boolean('on_hold')->default(1);
			$table->text('notes')->nullable();
			$table->date('receive_date')->nullable();
			$table->date('production_date')->nullable();
			$table->date('expiration_date')->nullable();
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
		Schema::dropIfExists('inventories');
	}
}
