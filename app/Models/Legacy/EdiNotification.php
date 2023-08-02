<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;
use Carbon\Carbon;

class EdiNotification extends BaseFacilityModel
{
    public $table = 'edinotification';
    public $primaryKey = 'EdiNotificationID';

    public function getEncodedOrder($schedule, $scheduleDetails, $ediOrderNumber, $ediAction)
    {
        $encodedOrder = [
            'EdiOrderNumber' => $ediOrderNumber,
            'EdiOrderDateTime' => Carbon::now()->format('Y-m-d H:i:s'),
            'EdiAction' => $ediAction,
            'Orders' => [
                [
                    'ScheduleID' => $schedule->getId(),
                    'ReleaseNumber' => $schedule->ReleaseNumber,
                    'PoNumber' => $schedule->PoNumber,
                    'OrderNumber' => $schedule->PoNumber,
                    'CustomerCode' => $schedule->CustomerCode,
                    'Shipper' => $schedule->ShipperID,
                    'Total1' => $schedule->Amount,
                    'ShipToID' => $schedule->ShipToID,
                    'TransactionTypeID' => $schedule->TransactionTypeID,
                    'Notes' => $schedule->Notes,
                    'ScheduleDateTime' => $schedule->ScheduleDateTime,
                ]
            ]
        ];

        if (!empty($scheduleDetails)) {
            foreach ($scheduleDetails as $detail) {

                $encodedOrder['Orders'][0]['Items'][] = [
                    'ActualItemID' => $detail->ItemID,
                    'EdiAction' => $detail->ediAction,
                    'ItemID' => $detail->item->ItemName,
                    'Total1' => $detail->Total1,
                    'UnitOfOrder' => $detail->SelectedUnitOfOrder,
                    'ItemDescription' => $detail->item->ItemDescription,
                    'LineNumber' => $detail->item->LineNumber,
                    'Total2' => $detail->Total2,
                    'Notes' => ''
                ];
            }
        }

        return base64_encode(json_encode($encodedOrder));
    }
}
