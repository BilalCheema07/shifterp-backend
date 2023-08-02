<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductShipping extends Model
{
	use HasFactory;
	protected $guarded = ['id'];


	public function safetyUnit(): BelongsTo
	{
		return $this->belongsTo(Unit::class, "safety_stock_unit");
	}

	public function parUnit(): BelongsTo
	{
		return $this->belongsTo(Unit::class, "par_level_unit");
	}
}
