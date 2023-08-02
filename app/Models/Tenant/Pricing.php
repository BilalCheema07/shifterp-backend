<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pricing extends Model
{
    use HasFactory, UUID;

    protected $guarded = ['id', 'uuid'];


	public function scopeWhereSearch($query, $search)
	{
		return 	$query->whereHas('customer', function ($inner_query) use ($search) {
			$inner_query->where('code', 'LIKE', '%'.$search.'%');
		})->orWhere('name', 'LIKE', '%'.$search.'%')
		->orWhereHas('category', function ($inner_query) use ($search) { 
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('product', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('unit', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('pricingType', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		})->orWhereHas('chargeType', function($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%');
		});
	}

	public function scopePricingTypeString($query, $pricing_type)
	{
		return $query->whereHas('pricingType', function ($query) use ($pricing_type) {
			$query->whereIn('uuid', $pricing_type);
		});
	}

    //Relations
	public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function category() :BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

    public function product() :BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function pricingType() :BelongsTo
	{
		return $this->belongsTo(PricingType::class);
	}

    public function chargeType() :BelongsTo
	{
		return $this->belongsTo(ChargeType::class);
	}
}
