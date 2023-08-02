<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseRevenue extends Model
{
    use HasFactory, UUID;

    protected $guarded = ['id', 'uuid'];
    
    //Scopes
    public function scopeRevenueTypeString($query, $revenue_type_id)
	{
		return $query->whereHas('revenueType', function ($query) use ($revenue_type_id) {
			$query->whereIn('uuid', $revenue_type_id);
		});
	}

    public function scopeFacilityString($query, $facility_id)
	{
		return $query->whereHas('facility', function ($query) use ($facility_id) {
			$query->whereIn('uuid', $facility_id);
		});
	}

    public function scopeRevenueItemString($query, $revenue_item_id)
	{
		return $query->whereHas('revenueItem', function ($query) use ($revenue_item_id) {
			$query->whereIn('uuid', $revenue_item_id);
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
		return $query->whereHas('revenueItem', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('customer', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('facility', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		});
	}

	//Relations
	public function revenueType() :BelongsTo
	{
		return $this->belongsTo(RevenueType::class);
	}

    public function revenueItem() :BelongsTo
	{
		return $this->belongsTo(RevenueItem::class);
	}

	public function shift() :BelongsTo
	{
		return $this->belongsTo(Shift::class);
	}

    public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

    public function facility() :BelongsTo
	{
		return $this->belongsTo(Facility::class);
	}
}
