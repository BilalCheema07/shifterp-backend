<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShippingOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function shipTo() : BelongsTo
    {
        return $this->belongsTo(ShipTo::class);
    }

    public function shipper() : BelongsTo
    {
        return $this->belongsTo(Shipper::class,'shipper_id');
    }

    public function StackType() :belongsTo
    {
        return $this->belongsTo(StackType::class);
    }
    
    public function ChargeType() :belongsTo
    {
        return $this->belongsTo(ChargeType::class);
    }

    
    
}
