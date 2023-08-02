<?php


namespace App\Models\Legacy;


use App\Helpers\Sql;
use App\Models\Admin\AccountFacility;
use App\Models\Admin\FacilitySetting;
use Illuminate\Database\Eloquent\Model;

class QbVendor extends Model
{
    protected $table = "qb_vendor";

    public static function getAllVendors($companyIdentifier)
    {
        $settings = AccountFacility::getCurrentFacility()->settings();

        $customClass = $settings->get(FacilitySetting::CUSTOM_CLASS);

        $builder = new static();

        if ($customClass) {
            $clientClass = new $customClass();

            if (method_exists($clientClass, "getVendorCustomFields")) {

                $builder = $clientClass->getVendorCustomFields($builder, $companyIdentifier);
            }
        }


        $builder
            ->groupBy('ListID')
            ->orderByRaw('ListID IS NULL, Name');
        dd(Sql::compile($builder));
        //    ->get();
    }

}
