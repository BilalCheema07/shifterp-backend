<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ReceivingOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function receivedFrom() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'receive_form');
    }


    public function shipper() : BelongsTo
    {
        return $this->belongsTo(Shipper::class);
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
