<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_orders', function (Blueprint $table) {
            $table->id();
			$table->foreignId('order_id')->constrained()->restrictOnDelete();
			$table->foreignId('shipper_id')->default(0)->constrained();
			$table->foreignId('ship_to_id')->default(0)->constrained();
			$table->foreignId('stack_type_id')->default(0)->constrained();
			$table->foreignId('charge_type_id')->default(0)->constrained();
			$table->boolean('is_remote_pick')->default(1);
			$table->boolean('is_allergen_pick')->default(1);
			$table->boolean('is_customer_called')->default(1);
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
        Schema::dropIfExists('shipping_orders');
    }
}
