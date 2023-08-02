<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueItem extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];
}
