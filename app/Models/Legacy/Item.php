<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;

class Item extends BaseFacilityModel
{
    public $connection = 'facility';
    public $table = 'item';
    public $primaryKey = 'ItemID';
    protected $guarded = [];

    const ITEM_CATEGORY_FINISHED_GOOD = 3;

    const ITEM_UNIT_OF_MEASURE_CASES = 'CS';

    public function getDescription($maxChar = null)
    {
        if (empty($maxChar)) {
            return $this->ItemDescription;
        } else {
            return substr($this->ItemDescription, 0, $maxChar - 1);
        }

    }

    public function getTotalOfUnit($lbsTotal, $unitOfMeasure = Item::ITEM_UNIT_OF_MEASURE_CASES)
    {
        // total2 / cs multiplier
        $multiplier = $this->getUnitOfMeasureConversion($unitOfMeasure);

        $unitTotal = $lbsTotal;

        if ($multiplier) {
            $unitTotal = round($unitTotal / $multiplier, 8);
        }

        return $unitTotal;
    }

    public function getEdiExternalProductCode()
    {
        return $this->EdiExternalProductCode ?? '0';
    }

    public function getEdiUpc()
    {
        return $this->EdiGtin;
    }

    public function getUnitOfMeasureConversion($unitOfMeasure = self::ITEM_UNIT_OF_MEASURE_CASES)
    {
        $field = 'ConvertToUnit';

        for ($x = 1; $x < 4; $x++) {
            $unitField = $field . $x;
            $multiplierField = $field . $x . 'Multiplier';

            if (!empty($this->{$unitField}) && $this->{$unitField} == $unitOfMeasure && !empty($this->{$multiplierField})) {
                return $this->{$multiplierField};
            }
        }

        return false;
    }

}
