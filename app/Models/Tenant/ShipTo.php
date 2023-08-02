<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipTo extends Model
{
    use HasFactory, UUID;
	
	protected $guarded = ['id', 'uuid'];
	
	//Search Query
	public function scopeSearchString($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%')
				->orWhere('external_id', 'LIKE', '%'.$search.'%')
			->orWhereHas('primaryContact', function($inner_query) use ($search) {
				$inner_query->where('name', 'LIKE', '%'.$search.'%');
				$inner_query->orWhere('email', 'LIKE', '%'.$search.'%');
			})->orWhereHas('customer', function($inner_query) use ($search) {
				$inner_query->where('name', 'LIKE', '%'.$search.'%');
			});
		});
	}

	//Relations
    public function primaryContact() :BelongsTo
	{
		return $this->belongsTo(PrimaryContact::class, 'primary_contact_id');
	}

	public function customer() :BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}
}
