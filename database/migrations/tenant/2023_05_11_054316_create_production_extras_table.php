<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_extras', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('unit_id')->default(0)->constrained();
            $table->string('name')->nullable();
            $table->string('amount')->nullable();
            $table->boolean('direct_material')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('production_extras');
    }
}
