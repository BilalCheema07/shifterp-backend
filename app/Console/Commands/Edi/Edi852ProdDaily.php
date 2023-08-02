<?php
namespace App\Console\Commands\Edi;

use App\Models\Legacy\Item;
use App\Services\Edi\FritoLayEdiDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Edi852ProdDaily extends EdiBaseCommand
{
    public $signature = 'edi:852daily {facility} {customerCode}';
    public $description = "Compiles and sends 852";

    public function handle()
    {
        $facility = $this->argument('facility');
        $customerCode = $this->argument('customerCode');

        app('tenant')->use($facility);

        $lastProductionId = $this->getLastProductionId();

        // Need to limit to the day prior.

        $itemsProduction = DB::connection('facility')->select("
            select
                sp.ScheduleProductionID,
                sp.ProductionDate,
                sp.Line1Total,
                sp.ExpirationDate,
                itm.EdiGtin,
                itm.EdiExternalProductCode,
                itm.ConvertToUnit1,
                itm.ConvertToUnit2,
                itm.ConvertToUnit3,
                itm.ConvertToUnit1Multiplier,
                itm.ConvertToUnit2Multiplier,
                itm.ConvertToUnit3Multiplier,
                itm.ItemDescription
            from
                `scheduleproduction` sp
            left join
                schedule s on s.ScheduleID = sp.ScheduleID
            left join
                scheduledetail sd on s.ScheduleID = sd.ScheduleID
            left join
                item itm on itm.ItemID = sd.ItemID
            where
                s.CustomerCode = '$customerCode'
                and sp.ScheduleProductionID > $lastProductionId
                and itm.EdiGtin is not null
                and s.ScheduleDateTime > 'start of yesterday' and s.ScheduleDateTime < 'end of yesterday'
            order by
                sp.ScheduleProductionID desc;
        ");

        $ediDocuments = [];

        $ediDoc = new FritoLayEdiDocument();

        $isaUniqueNumber = $this->getUniqueEdiID();

        $ediDoc->header($isaUniqueNumber, '852')
            ->xqSegment('today');

        $ediDoc->n9DailyInventorySegment();

        $ediDoc->n1Segment(['LW', 'FRITO LAY'])
            ->n1Segment(['VN', 'Zoroco Packaging', 'FA', '30027'])
            ->n3Segment('14702 Karcher Rd')
            ->n4Segment('Caldwell', 'ID', '83607');

        /** @var Item $item */
        foreach ($itemsProduction as $itemProduction) {

            $lbsTotal = $itemProduction->Line1Total;

            $item = new Item((array)$itemProduction);

            $totalCases = $item->getTotalOfUnit($lbsTotal, $item);

            $ediDoc->linSegment($item->getEdiUpc(), $item->getEdiExternalProductCode(), $item->getDescription(48));
            $ediDoc->zaSegment($totalCases);
        }

        $ediDoc->footer($isaUniqueNumber);

        $ediDocuments[$isaUniqueNumber] = $ediDoc->rawData();

        $filesystem = $this->getRemoteFilesystem();

        foreach ($ediDocuments as $uniqueID => $ediDocument) {

            $tmpFile = tempnam(storage_path(), 'edi852_');

            file_put_contents($tmpFile, $ediDocument);

            $fileName = '852_dailyprod_' . $uniqueID . '_' . Carbon::now()->format("Hi") . '.edi';

            $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);

            $this->uploadToSFTP($filesystem, '/Inbound/' . $fileName, $tmpFile);
        }
    }

    public function getLastProductionId()
    {


        return 35457;
    }
}
