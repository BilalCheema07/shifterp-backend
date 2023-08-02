<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('customer_id')->default(0)->constrained();
            $table->foreignId('product_id')->default(0)->constrained();
            $table->foreignId('category_id')->default(0)->constrained();
            $table->foreignId('pricing_type_id')->default(0)->constrained();
            $table->foreignId('charge_type_id')->default(0)->constrained();
            $table->foreignId('unit_id')->default(0)->constrained();
            $table->string('name')->nullable()->index();
            $table->string('lot_number')->nullable()->index();
            $table->string('lot_id1')->default(0)->index();
            $table->string('lot_id2')->default(0)->index();
            $table->string('grace_period')->nullable()->index();
            $table->string('min_charge')->nullable()->index();
            $table->string('price_per_unit')->nullable()->index();
            $table->boolean('status')->default(0)->index();
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
        Schema::dropIfExists('pricings');
    }
}
