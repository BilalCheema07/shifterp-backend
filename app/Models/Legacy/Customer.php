<?php


namespace App\Models\Legacy;


use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customer";

    public function scopeActive($query)
    {
        $query->where('Active', true);
    }
}
