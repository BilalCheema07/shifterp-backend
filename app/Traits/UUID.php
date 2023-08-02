<?php 

namespace App\Traits;

trait UUID
{
	public static function boot()
	{
		parent::boot();

		static::creating(function ($model) {
			$model->uuid = self::CreateUUID();
		});
	}

	public static function CreateUUID()
	{
		return str_replace('-', '', (string) \Webpatser\Uuid\Uuid::generate(4));
	}

	public function scopeWhereUUID($query, $uuid)
	{
		return $query->where('uuid', $uuid);
	}

	public function scopeWhereInUUID($query, $uuid)
	{
		return $query->whereIn('uuid', $uuid);
	}

	public static function getByUUID($uuid)
	{
		return static::whereInUUID($uuid)->get();
	}

	public static function findByUUID($uuid, array $columns = ['*'])
	{
		return static::whereUUID($uuid)->first($columns);
	}

	public static function findByUUIDOrFail($uuid, array $columns = ['*'])
	{
		return static::whereUUID($uuid)->firstOrFail($columns);
	}

}
