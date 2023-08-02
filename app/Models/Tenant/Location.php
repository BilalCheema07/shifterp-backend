<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;


class Location extends Model
{
    use HasFactory, UUID;
	
	protected $guarded = ['id', 'uuid'];
    
	public function scopeWhereSearch($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->where('custom_capacity', 'LIKE', '%'.$search.'%')->orWhere('name', 'LIKE', '%'.$search.'%');
		});
	}
}
