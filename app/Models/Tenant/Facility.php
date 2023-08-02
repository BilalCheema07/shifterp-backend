<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Facility extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	public function scopeSearchString($query, $search)
	{
		return $query->where(function ($inner_query) use ($search) {
			$inner_query->whereHas('primaryContact', function ($query) use ($search) {
				$query->where('fname', 'LIKE', '%'. $search.'%');
				$query->orWhere('lname', 'LIKE', '%'. $search.'%');
			})->orWhere('name', 'LIKE', '%'.$search.'%');
		});
	}

	//Relations
	public function files() :MorphToMany
	{
		return $this->morphToMany(File::class, 'fileable');
	}

	public function displayPic()
	{
		return $this->files()->where('type', 'fac_dp');
	}

	public function users() :BelongsToMany
	{
		return $this->belongsToMany(User::class, 'facility_users', 'facility_id', 'user_id');
	}

	public function customers() :BelongsToMany
	{
		return $this->belongsToMany(Customer::class);
	}

	public function primaryContact() :BelongsTo
	{
		return $this->belongsTo(User::class, 'admin_id');
	}
}