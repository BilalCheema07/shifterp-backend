<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlendOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blend_orders', function (Blueprint $table) {
            $table->id();
			$table->foreignId('order_id')->constrained()->restrictOnDelete();
			$table->foreignId('kit_id')->default(0)->constrained()->restrictOnDelete();
			$table->integer('quantity')->nullable();
			$table->foreignId('unit_id')->default(0)->constrained();
			$table->boolean('is_remote_pick')->default(1);
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
        Schema::dropIfExists('blend_orders');
    }
}
