<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	public function scopeSearchString($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->where('name', 'LIKE', '%'.$search.'%')
				->orWhere('code', 'LIKE', '%'.$search.'%');
		});
	}

	//Relations
    public function primaryContact()
	{
		return $this->belongsTo(PrimaryContact::class, 'primary_contact_id');
	}

	public function facilities() :BelongsToMany
	{
		return $this->belongsToMany(Facility::class);
	}
}
