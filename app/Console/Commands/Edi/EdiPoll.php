<?php

namespace App\Console\Commands\Edi;

use App\Models\Legacy\EdiNotification;
use App\Models\Legacy\Item;
use App\Models\Legacy\Schedule;
use App\Models\Legacy\ScheduleDetail;
use App\Models\Legacy\Shipper;
use App\Models\Legacy\ShipTo;
use App\Services\Edi\EdiDocument;
use App\Services\Edi\FritoLayEdiDocument;
use Carbon\Carbon;
use League\Flysystem\FileAttributes;

class EdiPoll extends EdiBaseCommand
{
    public $signature = 'edi:poll {facility} {customerCode}';
    public $description = "Gets EDI from SFTP and creates new orders";

    public $force997 = false;

    public function handle()
    {
        $facility = $this->argument('facility');

        app('tenant')->use($facility);

        $this->pollAndProcess();
    }

    public function pollAndProcess($fileToReprocess = null)
    {
        $customerEdiInfo = $this->getCustomerEdiInfo();
        $summaryTableData = [];
        $filesToProcess = [];

        $filesystem = $this->getRemoteFilesystem();

        if (empty($fileToReprocess)) {

            $getAvailableFiles = $filesystem->listContents(
                $customerEdiInfo->getEdiStorageInboundDir()
            );

            foreach ($getAvailableFiles as $fileAttributes) {
                /** @var FileAttributes $fileAttributes */

                $filePath = $fileAttributes->path();
                $fileDate = Carbon::parse($fileAttributes->lastModified())->format('Y-m-d H:i:s');
                $fileContents = $filesystem->read($filePath);

                $ediDoc = new FritoLayEdiDocument($fileContents);

                $filesToProcess[] = [
                    'path' => $filePath,
                    'lastModified' => $fileDate,
                    'contents' => $fileContents,
                    'edi' => $ediDoc,
                    'remoteFilesystem' => true
                ];

                // Upload EDI doc as is so we can reply if needed.
                $this->backupRawEdiFile($fileContents, 'fritoraw/' . $filePath);

            }

        } else {


            $fileContents = file_get_contents($fileToReprocess);

            $filesToProcess[] = [
                'path' => $fileToReprocess,
                'lastModified' => Carbon::now()->format('Y-m-d H:i:s'),
                'contents' => $fileContents,
                'remoteFilesystem' => false,
                'edi' => new FritoLayEdiDocument($fileContents)
            ];

        }

        foreach ($filesToProcess as $fileInfo) {

            /** @var FritoLayEdiDocument $ediDoc */
            $ediDoc = $fileInfo['edi'];

            if ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_997)) {

                $result = $this->process997($ediDoc);

                if ($result) {

                    if (!empty($fileInfo['remoteFileSystem'])) {
                        $filesystem->delete($filePath);
                    }

                    $summaryTableData[] = [
                        $filePath,
                        $fileDate,
                        $ediDoc->getType(),
                        '997 confirmed and removed from remote host'
                    ];
                }

            } elseif ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_856)) {

                $result = $this->process856($ediDoc);

                if ($result) {
                    $notes = 'Processed Successfully.';

                    $ediContent = $this->generate997($result, EdiDocument::EDI_DOCUMENT_TYPE_856);

                    if ($this->shouldGenerate997($fileInfo)) {

                        $tmpFile = tempnam(storage_path(), 'edi_');

                        $this->uploadToSFTP($filesystem, 'Inbound/' . 'edi997_' . Carbon::now()->timestamp . '.edi', $tmpFile, $ediContent->rawData());

                        $notes .= "   997 Generated and sent to remote file system.";
                    }
                } else {
                    $notes = "Failed to create EDI notification in ERP system";
                }

                /*
                $summaryTableData[] = [
                    $filePath,
                    $fileDate,
                    $ediDoc->getType(),
                   $notes
                ];
                */

            } elseif ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_940)) {

                $result = $this->process940($ediDoc);

                if ($result) {

                    $notes = 'Processed Successfully.';

                    $ediContent = $this->generate997($result, EdiDocument::EDI_DOCUMENT_TYPE_940);

                    if ($this->shouldGenerate997($fileInfo)) {
                        $tmpFile = tempnam(storage_path(), 'edi_');
                        $this->uploadToSFTP($filesystem, 'Inbound/' . 'edi997_' . Carbon::now()->timestamp . '.edi', $tmpFile, $ediContent->rawData());

                        $notes .= "   997 Generated and sent to remote file system.";
                    }
                } else {

                    $notes = "Failed to create EDI notification in ERP system";

                }

                $summaryTableData[] = [
                    $fileInfo['path'],
                    $fileInfo['lastModified'],
                    $ediDoc->getType(),
                    $notes
                ];

            } elseif ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_204)) {

                $result = $this->process204($ediDoc);

                if ($result) {

                    $notes = 'Processed Successfully.';

                    $ediContent = $this->generate997($result, EdiDocument::EDI_DOCUMENT_TYPE_204);

                    if ($this->shouldGenerate997($fileInfo)) {
                        $tmpFile = tempnam(storage_path(), 'edi_');
                        $this->uploadToSFTP($filesystem, 'Inbound/' . 'edi997_' . Carbon::now()->timestamp . '.edi', $tmpFile, $ediContent->rawData());

                        $notes .= "   997 Generated and sent to remote file system.";
                    }

                } else {

                    $notes = "Failed to create EDI notification in ERP system.";

                }

                $summaryTableData[] = [
                    $fileInfo['path'],
                    $fileInfo['lastModified'],
                    $ediDoc->getType(),
                    $notes
                ];

            }

            $this->backupRawEdiFile($ediDoc->rawData(true), 'processed/' . $ediDoc->getType() . '_' . date('H-i-s') . '.edi');

        }

        if (!empty($summaryTableData)) {
            $this->table(['File Name', 'Last Modified', 'Edi Type', 'Process Notes'], $summaryTableData);
        }


    }

    public function shouldGenerate997($fileInfo)
    {
        return (!empty($fileInfo['remoteFileSystem']) || $this->force997);
    }

    /**
    // Test processing.
    //$this->testProcessing940('new');
    //$this->testProcessing204();

    // Get from S3
    //$file = 'PepsiCo.ZOROCO.178149659.120621@172059';
    //$tmpFile = $this->getObjectContents('perfecterp-edi', '20211207/Outbound/' . $file);

    //$tmpFile = base_path('tests/edi/real/PepsiCo.ZOROCO.178148475.120621@094310');

    //$this->pollAndProcess($tmpFile);
     */
    public function testProcessing204()
    {
        $this->info("Testing 240 Processing...");
        $fileName = 'PepsiCo.ZOROCO.176372130.110221@132612 (204)';
        $fileContents = file_get_contents(base_path('/tests/edi/samples/' . $fileName));

        $ediDoc = new FritoLayEdiDocument($fileContents);

        //$this->backupRawEdiFile($fileContents, $fileName);

        $result = false;

        if ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_204)) {
            $result = $this->process204($ediDoc);
        }

        $this->info("Done.");
        dd($result);
    }

    public function testProcessing940($type)
    {
        $this->info("Testing 940 ($type) Processing...");

        if ($type == 'new') {
            $fileName = 'PepsiCo.ZOROCO.176370093.110221@132452 (940)';
            //$fileName = 'PepsiCo.ZOROCO.178130624.120621@094310';
        } elseif ($type == 'delete') {
            $fileName = 'PepsiCo.ZOROCO.176370093.110221@132452 (940-D)';
        } elseif ($type == 'update') {
            $fileName = 'PepsiCo.ZOROCO.176370093.110221@132452 (940-U)';
        }

        $fileContents = file_get_contents(base_path('/tests/edi/samples/' . $fileName));

        $ediDoc = new FritoLayEdiDocument($fileContents);

        $this->backupRawEdiFile($fileContents, $fileName);

        $result = false;

        if ($ediDoc->typeIs(EdiDocument::EDI_DOCUMENT_TYPE_940)) {

            $result = $this->process940($ediDoc);
        }

        $this->info("Done.");
        dd($result);
    }

    public function backupRawEdiFile($fileContents, $fileName)
    {
        $tmpFile = tempnam(storage_path(), 'edi_');
        file_put_contents($tmpFile, $fileContents);
        $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);

        unlink($tmpFile);
    }

    public function process204($edi)
    {
        $customerCode = $this->getCustomerCode();

        $poNumber = $edi->search('OID.3');

        $scheduleDateTime = $edi->search('G62.3');
        $externalShipperId = $edi->search('N1.5');

        $scheduleDateTime = Carbon::parse($scheduleDateTime . ' 01:00:00');

        $schedule = Schedule::where('PoNumber', $poNumber)->first();

        if ($schedule) {
            $shipper = Shipper::where('ExternalID', $externalShipperId)->first();

            if ($shipper) {
                $schedule->ShipperID = $shipper->ShipperID;
            } else {
                $this->error("Could not find shipper!  ($externalShipperId)");
            }

            $schedule->ScheduleDateTime = $scheduleDateTime;

            $this->info($schedule->ScheduleDateTime);
            $this->info($schedule->ShipperID);
            //$schedule->save();
        } else {

            $this->error("PO number ($poNumber) was not found in the system.");

            return false;
        }

        $ediNotification = new EdiNotification();
        $encodedOrder = $ediNotification->getEncodedOrder($schedule, null, $poNumber, 'update');

        $ediNotification->EdiType = EdiDocument::EDI_DOCUMENT_TYPE_204;
        $ediNotification->EdiFileName = EdiDocument::EDI_DOCUMENT_TYPE_204;
        $ediNotification->JsonOrder = $encodedOrder;
        $ediNotification->processed = 0;

        $ediNotification->save();

        return $poNumber;
    }

    public function process856($edi)
    {
        $edi->setLoopSegment('LIN');
        $edi->refreshSegments();

        $customerCode = $this->getCustomerCode();

        $ediOrderNumber = $edi->getType() . $edi->search('GS.7');

        $poNumber = $edi->search("PRF.2");
        $poNumber = ltrim($poNumber, '0');

        $releaseNumber = $edi->search("BSN.5");

        $scheduleDateTime = $edi->search("DTM.002.3");
        $scheduleDateTime = Carbon::parse($scheduleDateTime . ' 01:00:00');

        $currentLoopIndex = 1;

        $schedule = Schedule::where('PoNumber', $poNumber)->first();

        $ediAction = 'new';

        if (!$schedule) {
            $schedule =
                new Schedule([
                    'ReleaseNumber' => $releaseNumber,
                    'PoNumber' => $poNumber,
                    'ScheduleDateTime' => $scheduleDateTime,
                    'TransactionTypeID' => Schedule::ORDER_TYPE_RECEIVE,
                    'ScheduleStatusID' => Schedule::STATUS_NEW,
                    'CustomerCode' => $customerCode,
                    'User' => 'edi',
                    'EventDateTime' => Carbon::now()->format("Y-m-d H:i:s"),
                    'ScheduleUnitOfOrder' => Schedule::UNIT_OF_ORDER_CASES,
                ]);

            //$schedule->save();
        } else {
            $ediAction = 'update';
            $schedule->ScheduleDateTime = $scheduleDateTime;
        }

        while ($edi->ediLoopExists($currentLoopIndex)) {
            $searchKey = "LIN.$currentLoopIndex";

            $productQty = $edi->search("$searchKey.SN1.3");

            $externalProductCode = $edi->search("LIN.$currentLoopIndex.4");

            $this->info("Current Loop Index: $currentLoopIndex");
            $this->info("External Product Code: $externalProductCode");
            $item = Item::where('EdiExternalProductCode', $externalProductCode)->first();

            if ($item) {

                $this->info("Item found! (ItemID: $item->ItemID)");

                $scheduleDetail = ScheduleDetail::where('ScheduleID', $schedule->getId())->where('ItemID', $item->getId())->first();

                if ($scheduleDetail) {
                    $oldQty = $scheduleDetail->Total1;
                    $scheduleDetail->Total1 = $productQty;

                    $schedule->addToAmount($productQty - $oldQty);

                    $ediAction = 'update';

                    $scheduleDetail->ediAction = 'update';

                } else {

                    $scheduleDetail = new ScheduleDetail([
                        'ItemID' => $item->ItemID,
                        'Total1' => $productQty,
                        'SelectedUnitOfOrder' => Schedule::UNIT_OF_ORDER_CASES
                    ]);

                    $scheduleDetail->ediAction = 'new';
                }


                $shipToExternalId = $edi->search("N1.SF.5");

                $shipper = Shipper::where('ExternalID', $shipToExternalId)->first();

                if ($shipper) {
                    $schedule->ShipperID = $shipper->getId();
                }

                $schedule->Amount = $productQty;

                $scheduleDetail->item = $item;
                //$scheduleDetail->save();

                $scheduleDetails[] = $scheduleDetail;

            }

            $currentLoopIndex++;
        }

        $ediNotification = new EdiNotification();

        $encodedOrder = $ediNotification->getEncodedOrder($schedule, $scheduleDetails, $ediOrderNumber, $ediAction);

        $ediNotification->EdiType = EdiDocument::EDI_DOCUMENT_TYPE_856;
        $ediNotification->EdiFileName = EdiDocument::EDI_DOCUMENT_TYPE_856;
        $ediNotification->JsonOrder = $encodedOrder;
        $ediNotification->processed = 0;
        $ediNotification->RawEdiDoc = $edi->rawData(true);

        $ediNotification->save();

        return $poNumber;
    }

    /**
     * @param FritoLayEdiDocument $edi
     * @return mixed
     */
    public function process940($edi)
    {
        $customerCode = $this->getCustomerCode();

        $releaseNumber = $edi->search('W05.3', false);
        $poNumber = $edi->search('W05.4');

        $poNumber = ltrim($poNumber, '0');

        $ediOrderNumber = $edi->getType() . $edi->search('GS.7');

        $scheduleDateTime = $edi->search('G62.LC.3');

        $scheduleDateTime = Carbon::parse($scheduleDateTime . ' 01:00:00');

        $schedule = Schedule::where('PoNumber', $poNumber)->first();

        $scheduleDetails = [];

        $ediAction = 'new';

        if (!$schedule) {
            $schedule = new Schedule([
                'ReleaseNumber' => $releaseNumber,
                'PoNumber' => $poNumber,
                'ScheduleDateTime' => $scheduleDateTime,
                'TransactionTypeID' => Schedule::ORDER_TYPE_SHIP,
                'ScheduleStatusID' => Schedule::STATUS_NEW,
                'CustomerCode' => $customerCode,
                'User' => 'edi',
                'EventDateTime' => Carbon::now()->format("Y-m-d H:i:s"),
                'ScheduleUnitOfOrder' => Schedule::UNIT_OF_ORDER_CASES,
            ]);

            //$schedule->save();
        } else {
            $ediAction = 'update';
            $schedule->ScheduleDateTime = $scheduleDateTime;
        }

        $currentLoopIndex = 1;

        $orderType = 'ST';

        while ($edi->ediLoopExists($currentLoopIndex)) {

            $this->info('Loop Item: ' . $currentLoopIndex);

            $searchKey = "LX.$currentLoopIndex";

            $productQty = $edi->search("$searchKey.W01.2");
            $externalProductCode = $edi->search("$searchKey.W01.8");
            $unitOfOrder = $edi->search("$searchKey.W01.3");

            $scheduleDateTime = $edi->search("$searchKey.G62.02.3");
            $scheduleDateTime = Carbon::parse($scheduleDateTime . ' 01:00:00');

            $schedule->ScheduleDateTime = $scheduleDateTime;

            $this->info($externalProductCode);

            $item = Item::where('EdiExternalProductCode', $externalProductCode)->first();

            if ($item) {

                $this->info("Item found!");

                $action = $edi->search("$searchKey.N9.T8.3");

                $scheduleDetail = ScheduleDetail::where('ScheduleID', $schedule->getId())->where('ItemID', $item->getId())->first();

                // 00 - New, 01 - Cancel, 04 - Update QTY
                if ($action == '01') {

                    // Existing Schedule Detail?
                    if ($scheduleDetail) {
                        $schedule->addToAmount($scheduleDetail->Total1 * -1);

                        $scheduleDetail->ediAction = 'delete';
                    }

                    $ediAction = 'delete';
                } else {

                    if ($scheduleDetail) {
                        $oldQty = $scheduleDetail->Total1;
                        $scheduleDetail->Total1 = $productQty;

                        $schedule->addToAmount($productQty - $oldQty);

                        $ediAction = 'update';

                        $scheduleDetail->ediAction = 'update';

                    } else {

                        $scheduleDetail = new ScheduleDetail([
                            'ItemID' => $item->ItemID,
                            'Total1' => $productQty,
                            'SelectedUnitOfOrder' => Schedule::UNIT_OF_ORDER_CASES
                        ]);

                        $schedule->Amount += $productQty;

                        $scheduleDetail->ediAction = 'new';
                    }

                    $orderType = $edi->search("$searchKey.N1.2");

                    $shipToExternalId = $edi->search("$searchKey.N1.ST.5");

                    $shipTo = ShipTo::where('ExternalID', $shipToExternalId)->first();

                    if ($shipTo) {
                        $schedule->ShipToID = $shipTo->getId();
                    }

                    $schedule->Amount += $productQty;

                    $scheduleDetail->item = $item;
                    //$scheduleDetail->save();

                    $scheduleDetails[] = $scheduleDetail;
                }
            } else {
                $this->info("Item not found..");
            }

            //$schedule->save();

            $currentLoopIndex++;

        }

        $ediNotification = new EdiNotification();

        if ('ST' !== $orderType) {
            $schedule->TransactionTypeID = Schedule::ORDER_TYPE_RECEIVE;
        } else {
            $schedule->TransactionTypeID = Schedule::ORDER_TYPE_SHIP;
        }

        $encodedOrder = $ediNotification->getEncodedOrder($schedule, $scheduleDetails, $ediOrderNumber, $ediAction);

        $ediNotification->EdiType = EdiDocument::EDI_DOCUMENT_TYPE_940;
        $ediNotification->EdiFileName = EdiDocument::EDI_DOCUMENT_TYPE_940;
        $ediNotification->JsonOrder = $encodedOrder;
        $ediNotification->processed = 0;
        $ediNotification->RawEdiDoc = $edi->rawData(true);

        $ediNotification->save();

        return $poNumber;
    }

    /**
     * @param FritoLayEdiDocument $edi
     * @return bool
     */
    public function process997($edi)
    {
        return true;
    }

    /**
     * @param $ediTransactionId
     * @param $docType
     * @return FritoLayEdiDocument
     */
    public function generate997($ediTransactionId, $docType)
    {
        $ediDoc = new FritoLayEdiDocument();

        $isaUniqueNumber = $this->getUniqueEdiID();

        $ediDoc->headerAsSender($isaUniqueNumber, '997')
            ->add([
                'AK1*PD*1~',
                "AK2*$docType*$ediTransactionId~",
                "AK5*A~",
                "AK9*A*1*1*1~",
            ])
            ->footer($isaUniqueNumber);


        return $ediDoc;
    }
}
