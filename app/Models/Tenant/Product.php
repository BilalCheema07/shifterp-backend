<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	// public function scopeWhereCustomerUUID($query, $customer_ids)
	// {
	// 	return $query->whereHas('customer', function ($inner_query) use ($customer_ids) {
	// 		$inner_query->whereIn('uuid', $customer_ids);
	// 	});
	// }

	public function scopeWhereHighRisk($query, $val)
	{
		return $query->whereHas('shipping', function ($inner_query) use ($val) {
			$inner_query->where('is_high_risk', $val);
		});
	}

	public function scopeWhereCosted($query, $val)
	{
		return $query->whereHas('shipping', function ($inner_query) use ($val) {
			$inner_query->where('cost_item', $val);
		});
	}
	
	public function scopeSearchString($query, $search)
	{
		return 	$query->whereHas('customer', function ($inner_query) use ($search) {
					$inner_query->where('code', 'LIKE', '%'.$search.'%');
				})->orWhere('name', 'LIKE', '%'.$search.'%')
				->orWhere('description', 'LIKE', '%'.$search.'%')
				->orWhere('barcode', 'LIKE', '%'.$search.'%')
				->orWhereHas('category', function ($inner_query) use ($search) { 
					$inner_query->where('name', 'LIKE', '%'.$search.'%');
				})->orWhereHas('allergens', function($inner_query) use ($search) {
					$inner_query->where('name', 'LIKE', '%'.$search.'%');
				});
	}


	/*Relationships*/
	public function unit() :HasOne
	{
		return $this->hasOne(ProductUnit::class);
	}

	public function shipping() :HasOne
	{
		return $this->hasOne(ProductShipping::class);
	}

	public function allergens() :BelongsToMany
	{
		return $this->belongsToMany(Allergen::class, 'product_allergens', 'product_id', 'allergen_id');
	}

	public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function category() :BelongsTo
	{
		return $this->belongsTo(Category::class);
	}
}
