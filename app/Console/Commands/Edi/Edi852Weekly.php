<?php
namespace App\Console\Commands\Edi;

use App\Models\Legacy\Item;
use App\Services\Edi\EdiDocument;
use App\Services\Edi\FritoLayEdiDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Edi852Weekly extends EdiBaseCommand
{
    public $signature = 'edi:852weekly {facility} {customerCode} {--date=}';
    public $description = "Compiles and sends 852";

    public function handle()
    {
        $facility = $this->argument('facility');
        $customerCode = $this->argument('customerCode');
        $date = $this->option('date');

        app('tenant')->use($facility);

        $connection = DB::connection('facility');

        $inventoryItems = $connection->select("
            select
                   i.ItemID,
                   sum(i.Total2) as totalActiveInventory,
                   i.OnHold,
                   GROUP_CONCAT(distinct i.ReceiveDate SEPARATOR ',') as ReceiveDate,
                   itm.ConvertToUnit1,
                   itm.ConvertToUnit2,
                   itm.ConvertToUnit3,
                   itm.ConvertToUnit1Multiplier,
                   itm.ConvertToUnit2Multiplier,
                   itm.ConvertToUnit3Multiplier,
                   itm.EdiGtin,
                   itm.EdiExternalProductCode,
                   itm.ItemDescription
            from
                 inventory i
            left join
                item itm on itm.ItemID = i.ItemID
            where
                itm.CustomerCode = '$customerCode'
                and itm.ItemCategoryID = " . Item::ITEM_CATEGORY_FINISHED_GOOD . "
            group by
                i.ItemID, i.ExpirationDate, i.OnHold;
        ");

        # TODO: Add any shipments that were BARE.  If there is a shipment on Monday.
        /*
        $transactions = $connection->select("
            select
                   *
            from
                 transaction t
            left join
                item itm on itm.ItemID = t.ItemID
            where
                itm.CustomerCode = '$customerCode'
                and itm.ItemCategoryID = " . Item::ITEM_CATEGORY_FINISHED_GOOD . "
        ");
        */


        $ediDoc = new FritoLayEdiDocument();

        if (!empty($date)) {
            $date = Carbon::parse($date)->timestamp;
            $ediDoc->setStartFromDate($date);
        }

        $isaUniqueNumber = $this->getUniqueEdiID();

        $ediDoc->header($isaUniqueNumber, EdiDocument::EDI_DOCUMENT_TYPE_852)
            ->xqSegment();

        $ediDoc->n9WeeklyInventorySegment();

        $ediDoc->n1Segment(['LW', 'FRITO LAY'])
            ->n1Segment(['VN', 'Zoroco Packaging', 'FA', '30027'])
            ->n3Segment('14702 Karcher Rd')
            ->n4Segment('Caldwell', 'ID', '83607');

        $inventoryHoldMap = [];

        foreach ($inventoryItems as $inventoryItem) {

            $item = new Item((array)$inventoryItem);

            $inventoryHoldMap[$inventoryItem->ItemID]['item'] = $item;

            $inventoryHoldMap[$inventoryItem->ItemID]['OnHold'] = 0;
            $inventoryHoldMap[$inventoryItem->ItemID]['Active'] = 0;

            if ($inventoryItem->OnHold) {
                $inventoryHoldMap[$inventoryItem->ItemID]['OnHold'] = $item->getTotalOfUnit($inventoryItem->totalActiveInventory);
            } else {
                $inventoryHoldMap[$inventoryItem->ItemID]['Active'] = $item->getTotalOfUnit($inventoryItem->totalActiveInventory);
            }

        }

        /** @var Item $item */
        foreach ($inventoryHoldMap as $inventoryItem) {

            $item = $inventoryItem['item'];

            $ediDoc->linSegment($item->getEdiUpc(), $item->getEdiExternalProductCode(), $item->getDescription(48));

            $receiveDate = Carbon::parse($item->ReceiveDate)->format('Ymd');
            $expirationDate = Carbon::parse($item->ExpirationDate)->format('Ymd');

            $ediDoc->n9ExpirationSegment($expirationDate);

            $netActive = $inventoryItem['Active'] - $inventoryItem['OnHold'];

            if ($netActive < 0) {
                $netActive = 0;
            }

            $ediDoc->qtySegment('QO', '0')
                ->qtySegment('29', '0')
                ->qtySegment('MQ', $inventoryItem['Active'])
                ->qtySegment('QH', $inventoryItem['OnHold']) // Hold
                ->qtySegment('T5', '0');

            $ediDoc->zaSegment($netActive, 'QA','CA', $receiveDate);
            $ediDoc->zaSegment($netActive, 'QA','CA', $receiveDate);

        }

        $ediDoc->footer($isaUniqueNumber);

        $data = $ediDoc->rawData();

        $tmpFile = tempnam(storage_path(), 'edi852_');

        file_put_contents($tmpFile, $data);

        $fileName = '852_weeklyinventory_' . $isaUniqueNumber . '_' . Carbon::now()->format("Hi") . '.edi';

        $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);

        $filesystem = $this->getRemoteFilesystem();

        $this->uploadToSFTP($filesystem, '/Inbound/' . $fileName, $tmpFile);

        // TODO: Send email letting you know a new EDI Sent.

    }

}
