<?php
namespace App\Console\Commands\Edi;

use App\Models\Legacy\Inventory;
use App\Models\Legacy\Item;
use App\Models\Legacy\Schedule;
use App\Services\Edi\EdiDocument;
use App\Services\Edi\FritoLayEdiDocument;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class EdiSendOrder extends EdiBaseCommand
{
    public $signature = 'edi:sendorder {facility} {customerCode} {--scheduleId=}';
    public $description = "Sends either 944 or 945 depending on type of order";

    private $ediBucket = 'perfecterp-edi';

    public function handle()
    {
        $facility = $this->argument('facility');
        $customerCode = $this->argument('customerCode');

        app('tenant')->use($facility);

        if (!empty($this->option('scheduleId'))) {
            $scheduleId = $this->option('scheduleId');
            $this->processScheduleId($scheduleId);
            $this->info("Processed " . $scheduleId);
        }

        $objects = $this->listObjectsInBucket($this->ediBucket, strtoupper($customerCode) . '/schedule/process/');

        if (!empty($objects)) {

            foreach ($objects as $object) {

                $tmpFile = $this->getObjectContents('perfecterp-edi', $object['Key']);

                $scheduleId = file_get_contents($tmpFile);

                if (is_numeric($scheduleId)) {
                    $this->info("Found $scheduleId...  Processing.");

                    $this->deleteObject($this->ediBucket, $object['Key']);

                    $this->processScheduleId($scheduleId);

                    $newKey = str_replace("process", "processed", $object['Key']);

                    $this->uploadToS3($this->ediBucket, $newKey, $tmpFile);

                } else {
                    $this->info("Found {$object['Key']}... but it wasn't a valid file to process.  Removing.");
                    $this->deleteObject($this->ediBucket, $object['Key']);
                }

                unlink($tmpFile);
            }
        } else {
            $this->info("Nothing found to process");
        }

    }

    public function processScheduleId($scheduleId)
    {
        $customerCode = $this->getCustomerCode();

        $scheduleItems = DB::connection('facility')->select("
            select
                s.*,
                st.ExternalID as ShipToExternalID,
                sh.ExternalID as ShipperExternalID,
                sd.Total1,
                itm.EdiGtin,
                itm.EdiExternalProductCode,
                itm.ConvertToUnit1,
                itm.ConvertToUnit2,
                itm.ConvertToUnit3,
                itm.ConvertToUnit1Multiplier,
                itm.ConvertToUnit2Multiplier,
                itm.ConvertToUnit3Multiplier,
                itm.ItemDescription,
                itm.ItemID
            from
                `schedule` s
            left join
                scheduledetail sd on s.ScheduleID = sd.ScheduleID
            left join
                item itm on itm.ItemID = sd.ItemID
            left join
                shipto st on st.ShipToID = s.ShipToID
            left join
                shipper sh on sh.ShipperID = s.ShipperID
            where
                s.CustomerCode = '$customerCode'
                and s.ScheduleID = $scheduleId
                and itm.EdiGtin is not null
        ");

        // If the schedule is RC, then send 944.  If SH, send 945.
        $transactionType = $scheduleItems[0]->TransactionTypeID;

        if ($transactionType == Schedule::ORDER_TYPE_SHIP) {
            $this->process945($scheduleItems);
        } elseif ($transactionType == Schedule::ORDER_TYPE_RECEIVE) {
            $this->process944($scheduleItems);
        }
    }

    public function process944($scheduleItems)
    {
        $shipperId = $scheduleItems[0]->ShipperExternalID;
        $poNumber = $scheduleItems[0]->PoNumber;

        $ediDoc = new FritoLayEdiDocument();

        $isaUniqueNumber = $this->getUniqueEdiID();

        $ediDoc->headerAsSender($isaUniqueNumber, EdiDocument::EDI_DOCUMENT_TYPE_944, '0001', 'RE');

        $ediDoc->w17Segment($poNumber, 11);

        $scheduleDateTime = $scheduleItems[0]->ScheduleDateTime;
        $scheduleDateTime = Carbon::parse($scheduleDateTime);

        $ediDoc->n1Segment(['16', 'Zoroco', 'ZZ', '3791']) // shipper ID.  Need to move to facility edi settings.
        ->g62Segment('BS', $scheduleDateTime->format('Ymd'))
        ->g62Segment(11, Carbon::now()->format('Ymd'), Carbon::now()->format('HH') . '00');

        $lineNumber = 1;

        $total = 0;

        /** @var Item $item */
        foreach ($scheduleItems as $item) {

            $inventoryItem = Inventory::where('ItemID', $item->ItemID)->first();

            if ($inventoryItem) {
                $productionDate = Carbon::parse($inventoryItem->ProductionDate);
                $productionDate = $productionDate->format('Ymd');
            } else {
                $productionDate = Carbon::now()->format('Ymd');
            }

            $total += $item->Total1;

            $ediDoc->lxSegment($lineNumber)
                ->w07Segment(round($item->Total1), $item->EdiExternalProductCode)
                ->n9ManufactureDate($productionDate);

            $lineNumber++;
        }

        $ediDoc->w14Summary($total);

        $ediDoc->footer($isaUniqueNumber, '0001');

        $ediContents = $ediDoc->rawData();

        $tmpFile = tempnam(storage_path(), 'edi944_');

        file_put_contents($tmpFile, $ediContents);

        $fileName = '944_' . $isaUniqueNumber . '_' . Carbon::now()->format("Hi") . '.edi';

        $fileSystem = $this->getRemoteFilesystem();

        $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);
        $this->uploadToSFTP($fileSystem, 'Inbound/' . $fileName, $tmpFile);

        $this->info($ediContents);
    }

    public function process945($scheduleItems)
    {
        $shipToID = $scheduleItems[0]->ShipToExternalID;
        $poNumber = $scheduleItems[0]->PoNumber;

        $ediDoc = new FritoLayEdiDocument();

        $isaUniqueNumber = $this->getUniqueEdiID();

        $ediDoc->headerAsSender($isaUniqueNumber, '945');

        $ediDoc->w06Segment($poNumber);

        $ediDoc->n1Segment(['16', '', 'ZZ', '3791']) // shipper ID.  Need to move to facility edi settings.
        ->n1Segment(['ST', '', 'ZZ', $shipToID])
            ->n9Segment(98, 39061)
            ->g62Segment(11, Carbon::now()->format('Ymd'));

        $lineNumber = 1;

        /** @var Item $item */
        foreach ($scheduleItems as $item) {

            $inventoryItem = Inventory::where('ItemID', $item->ItemID)->first();

            if ($inventoryItem) {
                $productionDate = Carbon::parse($inventoryItem->ProductionDate);
                $productionDate = $productionDate->format('Ymd');
            } else {
                $productionDate = Carbon::now()->format('Ymd');
            }

            $ediDoc->lxSegment($lineNumber)
                ->w12Segment($item->Total1, $item->EdiExternalProductCode)
                ->g62Segment('BL', $productionDate);

            $lineNumber++;
        }

        $ediDoc->footer($isaUniqueNumber);

        $ediContents = $ediDoc->rawData();

        $tmpFile = tempnam(storage_path(), 'edi945_');

        file_put_contents($tmpFile, $ediContents);

        $fileName = '945_' . $isaUniqueNumber . '_' . Carbon::now()->format("Hi") . '.edi';

        $fileSystem = $this->getRemoteFilesystem();
        $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);
        $this->uploadToSFTP($fileSystem, 'Inbound/' . $fileName, $tmpFile);

        $this->info($ediContents);
    }

    public function getLastProductionId()
    {
        return 35457;
    }
}
