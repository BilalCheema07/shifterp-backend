<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_revenues', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->date('date')->default(now());
            $table->foreignId('customer_id')->default(0)->constrained();
            $table->foreignId('facility_id')->default(0)->constrained();
            $table->foreignId('revenue_type_id')->default(0)->constrained();
            $table->foreignId('revenue_item_id')->default(0)->constrained();
            $table->foreignId('shift_id')->nullable()->constrained();
            $table->string('amount')->nullable();
			$table->string('notes')->nullable();
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
        Schema::dropIfExists('expense_revenues');
    }
};
