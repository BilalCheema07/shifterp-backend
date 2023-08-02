<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionExtra extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	//Search Scope
	public function scopeWhereSearch($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%')
			->orWhereHas('unit', function($inner_query) use ($search) {
				$inner_query->where('name', 'LIKE', '%'.$search.'%');
		});
	});
	}


	//Relations
	public function unit() :BelongsTo
	{
		return $this->belongsTo(Unit::class);
	}
}
