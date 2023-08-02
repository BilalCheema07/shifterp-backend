<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrimaryContact extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];

	//Update Primary Contact
	public function scopeUpdatePrimaryContact($query, $request)
	{
		$request = (object)$request;
		
		return $query->update([
			'name' => $request->primary_contact_name,
			'email' => $request->primary_contact_email,
			'phone' => $request->primary_contact_phone
		]);
	}

	//Create Primary Contact
	public static function createPrimaryContact($request)
	{
		$request = (object)$request;

		return static::create([
			'name' => $request->primary_contact_name,
			'email' => $request->primary_contact_email,
			'phone' => $request->primary_contact_phone
		]);
	}

}
