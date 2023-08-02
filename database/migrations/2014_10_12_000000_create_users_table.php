<?php

use App\Enums\UserRoles;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
			$table->integer('tenant_user_id')->default(0);
			$table->foreignId('provision_account_id')->default(0);
			$table->string('username')->unique();
			$table->string('email')->unique();
            $table->string('phone');
			$table->string('password');
			$table->string('tenant_id')->nullable();
			$table->string('role');
            $table->tinyInteger('enable_sms')->default(0);
            $table->tinyInteger('enable_google')->default(0);
            $table->string('google2FA_secret')->nullable();
            $table->string('google2FA_key')->nullable();
            $table->string('updated_mail')->nullable();
            $table->string('updated_number')->nullable();
            $table->Integer('sms_code')->default(0);
            $table->string('sms_code_token')->nullable();
            $table->timestamp('code_expired_at')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
