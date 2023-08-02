<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DependencyType extends Model
{
    use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	//Relations
	public function dependencies()
	{
		return $this->hasMany(Dependency::class, "type_id");
	}
}
