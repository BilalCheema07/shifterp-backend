<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDependenciesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dependencies', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('type_id')->constrained('dependency_types')->restrictOnDelete();
			$table->string('name')->nullable();
			$table->string('module')->nullable();
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
		Schema::dropIfExists('dependencies');
	}
}
