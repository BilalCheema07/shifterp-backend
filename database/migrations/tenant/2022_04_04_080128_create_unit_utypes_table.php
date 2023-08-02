<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitUtypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('unit_utypes', function (Blueprint $table) {
			$table->foreignId('unit_id')->constrained()->onDelete('cascade');
			$table->foreignId('utype_id')->constrained()->onDelete('cascade');
			$table->boolean('is_active')->default(1);
			$table->primary(['unit_id', 'utype_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('unit_utypes');
	}
}
