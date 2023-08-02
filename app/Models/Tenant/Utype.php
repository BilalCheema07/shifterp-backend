<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Utype extends Model
{
	use HasFactory, UUID;
	protected $guarded = ['id', 'uuid'];

	//Relations
	public function units()
	{
		return $this->belongsToMany(Unit::class, 'unit_utypes', 'utype_id', 'unit_id');
	}
}
