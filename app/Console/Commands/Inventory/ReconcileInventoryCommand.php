<?php

namespace App\Console\Commands\Inventory;

use App\Console\Commands\BaseCommand;
use App\Models\Facility\Inventory;
use App\Models\Facility\Item;
use App\Models\Facility\Schedule;
use App\Models\Facility\Transaction;
use App\Models\Legacy\InventoryReconcile;
use Carbon\Carbon;

class ReconcileInventoryCommand extends BaseCommand
{
    public $signature = 'inventory:reconcile {facility}';
    public $description = "Reconciles inventory from inventory reconcile table into the inventory table.";

    public function handle()
    {
        $facility = $this->argument('facility');

        app('tenant')->use($facility);

        $reconcile = InventoryReconcile::all();

        $inventoryUpdateMap = [
              'ItemID',
              'Location',
              'Total1',
              'Total2',
              'Picked1',
              'Picked2',
              'LotNumber',
              'ReceiveDate',
              'ProductionDate',
              'ExpirationDate',
              'LotID1',
              'LotID2',
              'OnHold',
              'HoldCodeID',
              'PalletNumber',
              'CostPerUnitOfStock',
              'FreightPerUnitOfStock',
              'HoldNotes',
        ];

        $transactionMoveMap = [
            'ItemID',
            'Location',
            'Total1',
            'Total2',
            'LotNumber',
            'ReceiveDate',
            'ProductionDate',
            'ExpirationDate',
            'PalletNumber',
            'LotID1',
            'LotID2'
        ];

        $eventTypeGroup = [];
        $resultsTableData = [];
        $lastTrasaction = Transaction::orderBy('TransactionID', 'desc')->first();
        $nextTransactionID = $lastTrasaction->TransactionID + 1;

        $resultsTableHeader = ['ItemID', 'ReconcileID', 'InventoryID', 'TransactionID', 'Action', 'Notes'];

        foreach ($reconcile as $data) {

            // If the event type is empty, its an update.
            if (empty($data->EventType)) {

                $inventory = Inventory::where('InventoryID', $data->InventoryID)->first();

                if (!empty($inventory)) {

                    $changed = false;
                    $actionTaken = 'None';
                    $notes = "";

                    $transaction = new Transaction();

                    // Its a move.
                    if ($data->Location != $inventory->Location) {
                        $transaction->LocationPrevious = $inventory->location;
                        $transaction->TransactionTypeID = 'MO';
                        $transaction->Notes = "Yearly Reconcile " .  date('Y-m-d') . ": " . 'Moved from ' . $inventory->Location . ' to ' . $data->Location;

                        $changed = true;

                        $actionTaken = 'Move';
                        $notes .= 'Moved from ' . $inventory->Location . ' to ' . $data->Location . "\n";
                    }

                    if ($data->Total1 != $inventory->Total1 & $inventory->Total2 == $data->Total2) {
                        $transaction->TransactionTypeID = 'IA';
                        $transaction->AdjustCodeID = 4;
                        //$transaction->Notes = "Updated Total1 as part of yearly reconcile.  " . date('Y-m-d H:i:s');
                        $transaction->Notes = "Yearly Reconcile " .  date('Y-m-d') . ": " . 'Updated Total1 from ' . $inventory->Total1 . ' to ' . $data->Total1;

                        $changed = true;

                        $actionTaken = 'Update';

                        $transaction->Total1 = $data->Total1 - $inventory->Total1;

                        $notes .= 'Updated Total1 from ' . $inventory->Total1 . ' to ' . $data->Total1  . "\n";
                    }

                    if ($data->Total2 != $inventory->Total2 && $data->Total1 == $inventory->Total1) {
                        $transaction->TransactionTypeID = 'IA';
                        $transaction->Notes = "Updated Total2 as part of yearly reconcile.  " . date('Y-m-d H:i:s');
                        $transaction->Notes = "Yearly Reconcile " .  date('Y-m-d') . ": " .  'Updated Total2 from ' . $inventory->Total2 . ' to ' . $data->Total2;

                        $changed = true;

                        $actionTaken = 'Update';

                        $transaction->Total2 = $data->Total2 - $inventory->Total2;

                        $notes .= 'Updated Total2 from ' . $inventory->Total2 . ' to ' . $data->Total2 . "\n";
                    }

                    if ($data->Total2 != $inventory->Total2 && $data->Total1 != $inventory->Total1) {
                        $transaction->TransactionTypeID = 'IA';
                        $transaction->Notes = "Yearly Reconcile " .  date('Y-m-d') . ": " .  'Updated Total1 & Total2 from (Total1: ' . $inventory->Total1 . ' to ' . $data->Total1 . ', Total2: ' . $inventory->Total2 . ' to ' . $data->Total2 . ')';

                        $changed = true;

                        $actionTaken = 'Update';

                        $transaction->Total1 = $data->Total1 - $inventory->Total1;
                        $transaction->Total2 = $data->Total2 - $inventory->Total2;

                        $notes .= 'Updated Total1 & Total2 from (Total1: ' . $inventory->Total1 . ' to ' . $data->Total1 . ', Total2: ' . $inventory->Total2 . ' to ' . $data->Total2 . ')' . "\n";
                    }

                    if (empty($notes)) {
                        $notes = "No action taken.";
                    }

                    if ($changed) {
                        $notes = trim($notes, "\n");

                        $transaction->TransactionDateTime = date('Y-m-d H:i:s');
                        $transaction->EventDateTime = date('Y-m-d H:i:s');
                        $transaction->ReceiveDate = date('Y-m-d H:i:s');
                        $transaction->UserID = 0;

                        foreach ($transactionMoveMap as $k) {

                            // Don't update the value if it was set above.
                            if (!empty($transaction->$k)) {
                                continue;
                            }

                            if (false !== strpos($k, 'Date')) {
                                $date = date('Y-m-d H:i:s', strtotime($data->$k));

                                if (false !== strpos($date, '-0001') || false !== strpos($date, '0000')) {
                                    $inventory->$k = date('Y-m-d H:i:s');
                                } else {
                                    $inventory->$k = date('Y-m-d H:i:s', strtotime($data->$k));
                                }
                            } else {
                                $transaction->$k = $data->$k;
                            }
                        }

                        $transaction->save();

                        foreach ($inventoryUpdateMap as $k) {

                            if (false !== strpos($k, 'Date')) {
                                $date = date('Y-m-d H:i:s', strtotime($data->$k));

                                if (false !== strpos($date, '-0001') || false !== strpos($date, '0000')) {
                                    $inventory->$k = date('Y-m-d H:i:s');
                                } else {
                                    $inventory->$k = date('Y-m-d H:i:s', strtotime($data->$k));
                                }
                            } else {
                                $inventory->$k = $data->$k;
                            }
                        }

                        $inventory->save();

                        $resultsTableData[] = [
                            'ItemID' => $data->ItemID,
                            'ReconcileID' => $data->InventoryReconcileID,
                            'InventoryID' => $data->InventoryID,
                            'TransactionID' => $nextTransactionID++,
                            'Action' => $actionTaken,
                            'Notes' => $notes
                        ];
                    } else {
                        $resultsTableData[] = [
                            'ItemID' => $data->ItemID,
                            'ReconcileID' => $data->InventoryReconcileID,
                            'InventoryID' => $data->InventoryID,
                            'TransactionID' => "N/A",
                            'Action' => "None",
                            'Notes' => "No action was taken."
                        ];
                    }

                }

                continue;
            }

            // If event type is "D" its a delete.
            if ($data->EventType == 'D') {

                $transaction = new Transaction();
                $transaction->TransactionTypeID = 'IA';
                $transaction->TransactionDateTime = date('Y-m-d H:i:s');
                $transaction->EventDateTime = date('Y-m-d H:i:s');
                $transaction->ReceiveDate = date('Y-m-d H:i:s');
                $transaction->ProductionDate = date('Y-m-d H:i:s');
                $transaction->AdjustCodeID = 4;
                $transaction->Notes = "Adjustment added from from yearly reconcile (Deleted inventory, subtracting amounts). " . date('Y-m-d H:i:s');
                $transaction->UserID = 0;

                foreach ($transactionMoveMap as $k) {

                    if (false !== strpos($k, 'Date')) {
                        $date = date('Y-m-d H:i:s', strtotime($data->$k));

                        if (false !== strpos($date, '-0001') || false !== strpos($date, '0000')) {
                            $transaction->$k = date('Y-m-d H:i:s');
                        } else {
                            $transaction->$k = date('Y-m-d H:i:s', strtotime($data->$k));
                        }
                    } else {

                        if ($k == 'Total1' || $k == 'Total2') {
                            $transaction->$k = (-1) * $data->$k;
                        } else {
                            $transaction->$k = $data->$k;
                        }

                    }
                }

                $transaction->save();


                $inventory = Inventory::where('InventoryID', $data->InventoryID)->first();

                if (empty($inventory)) {
                    $resultsTableData[] = [
                        'ItemID' => $data->ItemID,
                        'ReconcileID' => $data->InventoryReconcileID,
                        'InventoryID' => $data->InventoryID,
                        'TransactionID' => "N/A",
                        'Action' => "Delete",
                        'Notes' => "No action taken.  Could not delete the inventory item because it did not exist."
                    ];

                    continue;
                }

                $resultsTableData[] = [
                    'ItemID' => $data->ItemID,
                    'ReconcileID' => $data->InventoryReconcileID,
                    'InventoryID' => $data->InventoryID,
                    'TransactionID' => "N/A",
                    'Action' => "Delete",
                    'Notes' => "Inventory item deleted.  No transaction added."
                ];

                Inventory::where('InventoryID', $data->InventoryID)->delete();

                continue;
            }


            // If event type is "A" its an add.
            if ($data->EventType == 'A') {

                $inventory = new Inventory();

                foreach ($inventoryUpdateMap as $k) {
                    if (false !== strpos($k, 'Date')) {

                        $date = date('Y-m-d H:i:s', strtotime($data->$k));

                        if (false !== strpos($date, '-0001') || false !== strpos($date, '0000')) {
                            $inventory->$k = date('Y-m-d H:i:s');
                        } else {
                            $inventory->$k = date('Y-m-d H:i:s', strtotime($data->$k));
                        }

                    } else {
                        $inventory->$k = $data->$k;
                    }
                }

                try {
                    $inventory->save();
                } catch (\Exception $e) {
                    dd($inventory);
                }


                $transaction = new Transaction();
                $transaction->TransactionTypeID = 'IA';
                $transaction->TransactionDateTime = date('Y-m-d H:i:s');
                $transaction->EventDateTime = date('Y-m-d H:i:s');
                $transaction->ReceiveDate = date('Y-m-d H:i:s');
                $transaction->ProductionDate = date('Y-m-d H:i:s');
                $transaction->AdjustCodeID = 4;
                $transaction->Notes = "Adjustment added from from yearly reconcile. " . date('Y-m-d H:i:s');
                $transaction->UserID = 0;

                foreach ($transactionMoveMap as $k) {

                    if (false !== strpos($k, 'Date')) {
                        $date = date('Y-m-d H:i:s', strtotime($data->$k));

                        if (false !== strpos($date, '-0001') || false !== strpos($date, '0000')) {
                            $transaction->$k = date('Y-m-d H:i:s');
                        } else {
                            $transaction->$k = date('Y-m-d H:i:s', strtotime($data->$k));
                        }
                    } else {
                        $transaction->$k = $data->$k;
                    }
                }

                try {
                    $transaction->save();
                } catch (\Exception $e) {
                    dd($transaction);
                }

                $resultsTableData[] = [
                    'ItemID' => $data->ItemID,
                    'ReconcileID' => $data->InventoryReconcileID,
                    'InventoryID' => $data->InventoryID,
                    'TransactionID' => "N/A",
                    'Action' => "Add",
                    'Notes' => "Inventory item added"
                ];

                continue;
            }

        }

        $this->table($resultsTableHeader, $resultsTableData);

        $path = storage_path('app/reconcile-results-2021-07-18.csv');

        $h = fopen($path, "w+");

        fputcsv($h, $resultsTableHeader);

        foreach ($resultsTableData as $data) {
            fputcsv($h, $data);
        }

        fclose($h);
    }
}
