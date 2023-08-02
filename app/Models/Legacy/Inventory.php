<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;

class Inventory extends BaseFacilityModel
{
    public $connection = 'facility';
    public $table = 'inventory';
}
