<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Role extends Model
{
	use HasFactory, UUID;
	protected $guarded = ['id', 'uuid'];

	protected $hidden = [
		'created_at',
		'updated_at'
	];
}
