<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KitProduct extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	//Relations
	public function kit() :BelongsTo
	{
		return $this->belongsTo(Kit::class);
	}
	
	public function alternatives() :HasMany
	{
		return $this->hasMany(KitProduct::class,'parent_id')->orderBy('priority','asc');
	}

	public function partType() :BelongsTo
	{
		return $this->belongsTo(PartType::class);
	}

	public function unit() :BelongsTo
	{
		return $this->belongsTo(Unit::class);
	}
	
	public function product() :BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
