<?php


namespace App\Models\Legacy;


use Illuminate\Database\Eloquent\Model;

class QbEmployee extends Model
{
    protected $table = "qb_employee";

    const FORKLIFT_FROZEN = "Forklift Frozen";
    const FORKLIFT_DRY    = "Forklift Dry";

    const FORKLIFT = [
        self::FORKLIFT_FROZEN,
        self::FORKLIFT_DRY
    ];
}
