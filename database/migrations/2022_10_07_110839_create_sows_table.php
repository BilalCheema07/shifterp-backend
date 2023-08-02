<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sows', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
			$table->foreignId('provision_account_id')->constrained();
            $table->string('name');
            $table->string('path');
			$table->string('extension');
            $table->date('billing_date');
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
        Schema::dropIfExists('sows');
    }
}
