<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use App\Traits\HasPermissionTrait;
use App\Models\Tenant\File;
use App\Models\Tenant\Facility;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;
	
	use UUID, HasPermissionTrait;
	/**
	* The attributes that are mass assignable.
	*
	* @var array<int, string>
	*/
	protected $guarded = ['id', 'uuid'];
	
	/**
	* The attributes that should be hidden for serialization.
	*
	* @var array<int, string>
	*/
	protected $hidden = [
		'id',
		'password',
		'remember_token',
	];
	protected $appends  = ['full_name'];
	
	
	/**
	* The attributes that should be cast.
	*
	* @var array<string, string>
	*/
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	
	
	public function getFullNameAttribute() : string
	{
		return $this->fname. ' ' .$this->lname;
	}
	
	public function scopeRoleString($query, $search)
	{
		return $query->whereHas('roles', function ($query) use ($search) {
			$query->whereIn('uuid', $search);
		});
	}
	
	public function scopeFacilityAdmins($query)
	{
		return $query->whereHas('roles', function ($query) {
			$query->where('slug', 'facility_admin');
		});
	}

	public function scopeSearchUser($query, $search)
	{
		return $query->where(function ($query) use ($search) {
			$query->where(DB::raw("concat(fname,' ',lname)"),'LIKE', '%'.$search.'%')
			->orWhere('username', 'LIKE', '%'.$search.'%')
			->orWhere('email', 'LIKE', '%'.$search.'%');
		});
	}
	
	
	/**
	* Relations
	*/
	public function facilities()
	{
		return $this->belongsToMany(Facility::class, 'facility_users', 'user_id', 'facility_id')->withPivot('is_active');
	}
	
	public function files()
	{
		return $this->morphToMany(File::class, 'fileable');
	}
	
	public function profile_pic()
	{
		return $this->files()->where('type', 'profile_pic');
	}
}
