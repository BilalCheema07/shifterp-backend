<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Driver extends Model
{
    use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

    public function orders() :BelongsToMany
    {
        return $this->belongsToMany(Order::class,'orders_drivers','driver_id','order_id')->withPivot('type');
    }
}
