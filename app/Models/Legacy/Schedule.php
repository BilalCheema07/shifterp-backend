<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;

class Schedule extends BaseFacilityModel
{
    public $table = 'schedule';
    public $primaryKey = 'ScheduleID';

    const ORDER_TYPE_PRODUCTION = 'PR';
    const ORDER_TYPE_SHIP = 'SH';
    const ORDER_TYPE_RECEIVE = 'RC';

    const STATUS_NEW = 'N';
    const UNIT_OF_ORDER_CASES = 'CS';

    public function addToAmount($value)
    {
        $this->Amount += $value;

        if ($this->Amount < 0) {
            $this->Amount = 0;
        }
    }
}
