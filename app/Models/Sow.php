<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sow extends Model
{
	use HasFactory, UUID;
	
	protected $guarded = ['id', 'uuid'];

	public function getUrlAttribute()
	{
		return url('/').'/public/file_upload/'.$this->path;
	}

	//Relations
	public function provisionAccount() :BelongsTo
	{
		return $this->belongsTo(ProvisionAccount::class, 'provision_account_id');
	}
}
