<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Database\Concerns;
use Stancl\Tenancy\Events;
class Domain extends Model implements Contracts\Domain
{
    use Concerns\CentralConnection,
        Concerns\EnsuresDomainIsNotOccupied,
        Concerns\ConvertsDomainsToLowercase,
        Concerns\InvalidatesTenantsResolverCache;

    protected $guarded = [];
    protected $fillable = [
        'dba_name',
        'display_name',
        'domain',
    ];

    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'));
    }
}