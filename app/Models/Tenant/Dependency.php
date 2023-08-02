<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dependency extends Model
{
    use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	//Relations
	public function type()
	{
		return $this->belongsTo(DependencyType::class, "type_id");
	}
}
