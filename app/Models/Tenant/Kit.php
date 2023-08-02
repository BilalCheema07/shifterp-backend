<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kit extends Model
{
	use HasFactory, UUID;
	
	protected $guarded = ['id', 'uuid'];

	
	public function scopeWhereCustomerUUID($query, $customer_id)
	{
		return $query->whereHas('customer', function ($inner_query) use ($customer_id) {
			$inner_query->whereUUID($customer_id);
		});
	}

	public function scopeWhereCustomerUUIDs($query, $customer_ids)
	{
		return $query->whereHas('customer', function ($inner_query) use ($customer_ids) {
			$inner_query->whereIn('uuid', $customer_ids);
		});
	}

	public function scopeWhereProductUUID($query, $product_id)
	{
		return $query->whereHas('products', function ($inner_query) use ($product_id) {
			$inner_query->whereUUID($product_id);
		});
	}

	public function scopeWhereSearch($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->whereHas('customer', function ($query) use ($search) {
				$query->where('name', 'LIKE', '%'. $search.'%');
				$query->orWhere('code', 'LIKE', '%'. $search.'%');
			})->orWhere('name', 'LIKE', '%'.$search.'%');
		});
	}

	/*Relationships*/
	public function products() :BelongsToMany
	{
		return $this->belongsToMany(Product::class, 'kit_products');	
	}

	public function kitProducts() :HasMany
	{
		return $this->hasMany(KitProduct::class);
	}

	public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}
}
