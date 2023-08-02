<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
	use HasFactory, UUID;

	protected $guarded = ["id", "uuid"];

	//Get Duration Record of Orders
	public function scopeGetDurationRecord($query, $date, $duration)
	{
		if ($duration == "week") {
			$dt = Carbon::parse($date);
			$weekStartDate = $dt->startOfWeek()->format("Y-m-d");
			$weekEndDate = $dt->endOfWeek()->format("Y-m-d");

			return $query->whereBetween("date", [$weekStartDate, $weekEndDate]);
		} else if ($duration == "day") {
			return $query->where("date",$date);
		} else {
			
		}
	}

	//Create Order Function
	public static function createOrder($request, $type, $connected_order_id = 0)
	{
		$request = (object)$request;
		$random_status = array("new", "remote", "ready", "note", "completed", "not_enough");
		$order_status = array_rand($random_status,1);
		$status = $random_status[$order_status];

		$order =  static::create([
			"customer_id"       => customerId($request->customer_id),
			"type"              => $type,
			"date"              => date("Y-m-d", strtotime($request->date)),
			"time"              => $request->time,
			"po_number"         => isset($request->po_number) ? $request->po_number : "",
			"release_number"    => @$request->release_number ?? "",
			"po_notes"          => $request->po_notes,
			"notes"             => $request->notes,
			"updated_by"        => auth()->user()->username,
			"parent_order_id"   => $connected_order_id,
			"status"            => $status
		]);
		
		$order->drivers()->attach(DriverId(@$request->driver1_id), ["type"=> 1]);
		$order->drivers()->attach(DriverId(@$request->driver2_id), ["type"=> 2]);
		$order->schedule_id = $order->id +1000;
		$order->update();
		
		return $order; 
	}   

	//Relations
	public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function blendOrder() :HasOne
	{
		return $this->hasOne(BlendOrder::class);
	}

	public function productionOrder() :HasOne
	{
		return $this->hasOne(ProductionOrder::class);
	}

	public function shippingOrder() :HasOne
	{
		return $this->hasOne(ShippingOrder::class);
	}

	public function receivingOrder() :HasOne
	{
		return $this->hasOne(ReceivingOrder::class);
	}
	
	public function drivers() :BelongsToMany
	{
		return $this->belongsToMany(Driver::class, "orders_drivers", "order_id", "driver_id")->withPivot("type");
	}

	public function connectedParentOrder() :BelongsTo
	{
		return $this->belongsTo(Order::class, "parent_order_id");
	}

	public function connectedChildOrders() :HasMany
	{
		return $this->hasMany(Order::class, "parent_order_id");
	}
}
