<?php


namespace App\Contracts;


interface Tenant
{

    public function use($tenant, $callback = null);

}
