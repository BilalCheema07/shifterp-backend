<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class PartType extends Model
{
    use HasFactory, UUID;

    protected $guarded = ['id', 'uuid'];
}
