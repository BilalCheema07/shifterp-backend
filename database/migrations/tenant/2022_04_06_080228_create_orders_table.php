<?php

// use App\Enums\Tenant\OrderStatus;
// use App\Enums\Tenant\OrderType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid');
			$table->foreignId('customer_id')->constrained()->restrictOnDelete();
			$table->enum('type', ['shipping', 'receiving', 'production', 'blend']);
			// $table->unsignedTinyInteger('type');
			$table->date('date')->nullable();
			$table->time('time')->nullable();
			$table->unsignedInteger('schedule_id')->default(0);
			$table->string('po_number')->nullable();
			$table->string('release_number')->nullable();
			$table->string('po_notes')->nullable();
			$table->string('notes')->nullable();
			$table->string('updated_by')->nullable();
			$table->enum('status', ['new', 'remote', 'ready', 'note', 'completed', 'not_enough', 'cancel'])->default('new');
			$table->unsignedInteger('parent_order_id')->default(0);
			// $table->unsignedTinyInteger('status')->default(OrderStatus::NEW);
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
		Schema::dropIfExists('orders');
	}
}
