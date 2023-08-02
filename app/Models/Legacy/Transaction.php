<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;

class Transaction extends BaseFacilityModel
{
    public $connection = 'facility';
    public $table = 'transaction';
}
