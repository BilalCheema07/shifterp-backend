<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->string('fname');
			$table->string('lname');
			$table->string('username');
			$table->string('password');
			$table->rememberToken();
			$table->string('phone');
			$table->string('email')->unique();
			$table->string('address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('job_title')->nullable();
			$table->string('department')->nullable();
			$table->string('supervisor_name')->nullable();
			$table->string('shift')->nullable();
			$table->date('birth_date')->nullable();
			$table->date('hire_date')->nullable();
			$table->date('release_date')->nullable();
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
		Schema::dropIfExists('users');
	}
}
