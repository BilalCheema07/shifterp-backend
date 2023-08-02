<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	//Relations
	public function Roles() :BelongsToMany
	{
		return $this->belongsToMany(Role::class,'roles_permissions');
	}

	public function Users() :BelongsToMany
	{
		return $this->belongsToMany(User::class,'users_permissions');
	}

	public function Module() :BelongsTo
	{
		return $this->belongsTo(Module::class,'modules_permissions');
	}

	public function children() :HasMany
	{
		return $this->hasMany(Permission::class, 'parent_id', 'id');
	}
}
