<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipTosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ship_tos', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('primary_contact_id')->default(0)->constrained();
			$table->foreignId('customer_id')->constrained()->onDelete('cascade');
			$table->string('name')->nullable();
			$table->integer('external_id')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('state')->nullable();
			$table->string('city')->nullable();
			$table->string('zip_code')->nullable();
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
		Schema::dropIfExists('ship_tos');
	}
}
