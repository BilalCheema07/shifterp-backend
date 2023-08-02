<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUnit extends Model
{
	use HasFactory;
	protected $guarded = ['id'];

	// units relationships
	public function stockUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_stock");
	}

	public function orderUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_order");
	}

	public function purchaseUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_purchase");
	}

	public function countUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_count");
	}

	public function packageUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_package");
	}

	public function sellUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_sell");
	}

	public function assemblyUnit() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "unit_of_assembly");
	}

	public function varUnit1() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "variable_unit1");
	}

	public function varUnit2() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "variable_unit2");
	}

	public function conUnit1() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "convert_to_unit1");
	}

	public function conUnit2() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "convert_to_unit2");
	}

	public function conUnit3() :BelongsTo
	{
		return $this->belongsTo(Unit::class, "convert_to_unit3");
	}
}
