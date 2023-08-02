<?php

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
			$table->foreignId('provision_account_id')->constrained();
            $table->foreignId('subscription_id')->constrained();
            $table->date('recurring_billing_start_date');
            $table->integer('setup_fee');
            $table->date('setup_fee_start_date');
            $table->date('sub_expire_date')->nullable();
            $table->integer('total');
            $table->enum('status', ['active', 'in-processing', 'cancel', 'pause'])->default('active');
            // $table->unsignedTinyInteger('status')->default(SubscriptionStatus::ACTIVE);
            $table->date('pause_start_date')->nullable();
            $table->integer('pause_subscription_months')->nullable();
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
        Schema::dropIfExists('subscription_details');
    }
}
