<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductionOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function kit() : BelongsTo
    {
        return $this->belongsTo(Kit::class);
    }

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

}
