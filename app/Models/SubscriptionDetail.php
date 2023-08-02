<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDetail extends Model
{
    use HasFactory, UUID;
    
	protected $guarded = ['id', 'uuid'];
	
	//Relations
	public function subscription() :BelongsTo
	{
		return $this->belongsTo(Subscription::class, 'subscription_id');
	}

	public function provisionAccount() :BelongsTo
	{
		return $this->belongsTo(ProvisionAccount::class, 'provision_account_id');
	}
}
