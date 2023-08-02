<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiving_orders', function (Blueprint $table) {
            $table->id();
			$table->foreignId('order_id')->constrained()->restrictOnDelete();
			$table->foreignId('shipper_id')->default(0)->constrained();
			$table->string('receive_form')->nullable();
			$table->integer('quantity')->nullable();
			$table->foreignId('unit_id')->default(0)->constrained();
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
        Schema::dropIfExists('receiving_orders');
    }
}
