<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingContact extends Model
{
    use HasFactory, UUID;
    protected $guarded = ['id', 'uuid'];

	public function provisionAccount() :BelongsTo
	{
		return $this->belongsTo(ProvisionAccount::class, 'provision_account_id');
	}
}
