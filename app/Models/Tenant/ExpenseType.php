<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    use HasFactory, UUID;

    protected $guarded = ['id', 'uuid'];
    
    protected $hidden =[
        'created_at',
        'updated_at'
    ];

    //Relations
    public function children() :HasMany
	{
		return $this->hasMany(ExpenseType::class, 'parent_id', 'id');
	}
}
