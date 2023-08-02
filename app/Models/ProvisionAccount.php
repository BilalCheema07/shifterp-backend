<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProvisionAccount extends Model
{
    use HasFactory, UUID;
    protected $guarded = ['id', 'uuid'];

	//Filters Queries
	public function scopeSearchType($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->whereHas('subDetails', function ($query) use ($search) {
				$query->whereIn('subscription_id', $search);
			});
		});
	}

	public function scopeSearchStatus($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->whereHas('subDetails', function ($query) use ($search) {
				$query->whereIn('status',$search );
			});
		});
	}

	public function scopeSearchString($query, $search)
	{
		return $query->where('company_name', 'LIKE', '%'.$search.'%');
	}

	//Relations
	public function billingContact() :HasOne
	{
		return $this->hasOne(BillingContact::class, 'provision_account_id');
	}
    
	public function user() :HasOne
	{
		return $this->hasOne(User::class, 'provision_account_id','id');
	}

	public function subDetails() :HasMany
	{
		return $this->hasMany(SubscriptionDetail::class);
	}

	public function subHistory() :HasMany
	{
		return $this->hasMany(SubscriptionHistory::class);
	}

	public function sowUploads() :HasMany
	{
		return $this->hasMany(Sow::class);
	}

}
