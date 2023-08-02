<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revenue extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	public function scopeRevenueTypeString($query, $revenue_type)
	{
		return $query->whereHas('revenueType', function ($query) use ($revenue_type) {
			$query->whereIn('uuid', $revenue_type);
		});
	}

	public function scopeDate($query, $date)
	{
		return $query->where(function ($inner_query) use ($date) {
			$inner_query->where('date', $date);
		});
	}

	public function scopeWhereSearch($query, $search)
	{
		return $query->whereHas('revenueType', function($query) use ($search) {
			$query->where('name', 'LIKE', '%'.$search.'%');
		});
	}

	//Relations
	public function revenueType() :BelongsTo
	{
		return $this->belongsTo(RevenueType::class);
	}

	public function shift() :BelongsTo
	{
		return $this->belongsTo(Shift::class);
	}
}
