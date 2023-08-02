<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('primary_contact_id')->constrained();
			$table->string('name')->nullable();
			$table->string('code', 30)->unique();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('production_pick_logic')->nullable();
			$table->string('shipping_pick_logic')->nullable();
			$table->string('min_charge')->nullable();
			$table->boolean('lot_number')->default(0);
			$table->boolean('lot_id1')->default(0);
			$table->boolean('lot_id2')->default(0);
			$table->boolean('receive_date')->default(0);
			$table->boolean('production_date')->default(0);
			$table->boolean('expiration_date')->default(0);
			$table->boolean('billed_date')->default(0);
			$table->boolean('show_unit_of_count')->default(0);
			$table->boolean('group_by_item')->default(0);
			$table->boolean('group_by_lot_number')->default(0);
			$table->boolean('group_by_lot_id1')->default(0);
			$table->boolean('group_by_lot_id2')->default(0);
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
		Schema::dropIfExists('customers');
	}
}
