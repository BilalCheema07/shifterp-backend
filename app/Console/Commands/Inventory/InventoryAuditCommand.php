<?php

namespace App\Console\Commands\Inventory;

use App\Console\Commands\BaseCommand;
use App\Models\Facility\Inventory;
use App\Models\Facility\Item;
use App\Models\Facility\Schedule;
use App\Models\Facility\Transaction;
use Carbon\Carbon;

class InventoryAuditCommand extends BaseCommand
{
    public $signature = 'inventory:audit {facility}';
    public $description = "Reviews transactions from the date specified.";


    private function useFacility($facilityName)
    {
        $facilityConfig = app('config')->get('database.connections.facility');

        $facilityConfig['database'] = $facilityName;

        app('config')->set('database.connections.facility', $facilityConfig);

        app('db')->reconnect('facility');
    }

    private function getTransactionInventoryComparison($itemId, $auditStartDate)
    {
        $inventory = Inventory::where('ItemID', $itemId)->orderBy('ReceiveDate', 'desc')->get();
        $transactions = Transaction::where('ItemID', $itemId)->whereIn('TransactionTypeID', ['SH','IA','RC','PR'])->get();
        $itemDetails = Item::where('ItemID', $itemId)->first();

        $totalInInventory = 0;
        $inventoryReceiveDates = [];
        $transactionScheduleIds = [];

        foreach ($inventory as $item) {
            $totalInInventory += $item->Total2;
            $inventoryReceiveDates[$item->ReceiveDate] = $item->ReceiveDate;
        }

        $totalAfterTransactions = 0;
        foreach ($transactions as $transaction) {
            $totalAfterTransactions += $transaction->Total2;
            $transactionScheduleIds[$transaction->ScheduleID] = $transaction->ScheduleID;
        }

        // Filter the schedule ids down to just the ones within the audit window.
        $schedule = Schedule::whereIn('ScheduleID', $transactionScheduleIds)->get();

        $transactionScheduleIds = [];

        foreach ($schedule as $s) {
            $transactionScheduleIds[$s->ScheduleID] = $s->ScheduleID;
        }

        return [
            'ItemID' => $itemId,
            'ItemName' => $itemDetails->ItemName,
            'ItemDescription' => $itemDetails->ItemDescription,
            'Total Inventory' => round($totalInInventory, 4),
            'Total Received/Used' => round($totalAfterTransactions, 4),
            'Difference' => (round($totalInInventory, 2) - round($totalAfterTransactions, 2)),
            'Received On' => implode(',', $inventoryReceiveDates),
            'Used In WO' => count($transactionScheduleIds)
        ];
    }

    public function handle()
    {

        $facility = $this->argument('facility');

        $this->useFacility($facility);

        $auditStartDate = '2020-06-26';

        // Get all of the transactions within the audit period.
        $transactions = Transaction::where('TransactionDateTime', '>=', $auditStartDate)->whereIn('TransactionTypeID', ['SH','IA','RC','PR'])->orderBy('TransactionDateTime', 'desc')->get();

        $transactionsByItemID = [];

        foreach ($transactions as $t) {
            $transactionsByItemID[$t->ItemID][] = $t;
        }

        $tableData = [];

        $pb = $this->output->createProgressBar(count($transactionsByItemID));

        foreach ($transactionsByItemID as $itemId => $transactions) {
            $tableData[] = $this->getTransactionInventoryComparison($itemId, $auditStartDate);

            $pb->advance();
        }

        $pb->finish();
        $this->output->newLine(2);

        $tableHeaders = ['ItemID', 'ItemName', 'ItemDescription', 'Total Inventory', 'Total Received/Used', 'Difference', 'Received On', 'Used in WO'];

        $this->output->table($tableHeaders, $tableData);

        $fileName = 'inventory-audit-' . Carbon::now()->format('Y-m-d') . '.csv';
        $filePath = base_path($fileName);
        $this->exportToCSV($tableHeaders, $tableData, $filePath);

        $data = $this->uploadToS3('perfecterp-reports', $fileName, $filePath);
        dd($data);

    }
}
