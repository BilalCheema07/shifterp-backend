<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory, UUID;
	protected $guarded = ['id', 'uuid'];

	public function types() :BelongsToMany
	{
		return $this->belongsToMany(Utype::class, 'unit_utypes', 'unit_id', 'utype_id');
	}
}
