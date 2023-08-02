<?php


namespace App\Contracts;


interface EdiSegment
{

    public function use($tenant, $callback = null);

}
