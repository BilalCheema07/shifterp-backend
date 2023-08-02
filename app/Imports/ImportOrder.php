<?php

namespace App\Imports;

use App\Jobs\VerifyEmailJob;
use App\Mail\ImportOrderMail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Tenant\{Kit, Unit, Customer, Order, Shipper, ProductionOrder, ReceivingOrder, ShippingOrder, BlendOrder, ChargeType, Driver, StackType, ShipTo};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class ImportOrder implements 
    ShouldQueue,
    ToCollection,
    WithStartRow,
    WithHeadingRow,
    WithValidation,
    WithChunkReading
{
    use Importable, RegistersEventListeners;
    public $skip;

    public function __construct($skip = 0)
    {
        $this->skip = $skip;
    }

    public function collection(Collection $orders)
    {
        foreach ($orders->skip($this->skip) as $row) 
        {
            $customer = Customer::where('code', 'LIKE', '%'.$row['customer_code'].'%')->firstOrFail()->id;
            $kit = Kit::where('name', 'LIKE', '%'.$row['kit_name'].'%')->firstOrFail()->id;
            $unit = Unit::where('name', 'LIKE', '%'.$row['unit_name'].'%')->firstOrFail()->id;
            $shipper = Shipper::where('shipper_name', 'LIKE', '%'.$row['shipper_name'].'%')->firstOrFail()->id;
            $ship_to = ShipTo::where('name', 'LIKE', '%'.$row['ship_to_name'].'%')->firstOrFail()->id;
            $stack_type = StackType::where('name', 'LIKE', '%'.$row['stack_type_name'].'%')->firstOrFail()->id;
            $charge_type = ChargeType::where('name', 'LIKE', '%'.$row['charge_type_name'].'%')->firstOrFail()->id;

            $order =  Order::create([
                'customer_id' => $customer,
                'type' => $row['type'],
                'date' => date('Y-m-d', strtotime($row['date'])),
                'time' => $row['time'],
                'schedule_id' => rand(000000, 999999),
                'po_number' => $row['po_number'],
                'release_number' => $row['release_number'],
                'po_notes' => $row['po_notes'],
                'notes' => $row['notes'],
                'updated_by' => auth()->user()->username ?? 'admin',
            ]);

            $driver1 = Driver::where('name', 'LIKE', '%'.$row['driver1'].'%')->firstOrFail();
            $order->drivers()->attach(($driver1->id), ["type"=> 1]);
            
            $driver2 = Driver::where('name', 'LIKE', '%'.$row['driver2'].'%')->firstOrFail();
            $order->drivers()->attach(($driver2->id), ["type"=> 2]);
            
            if ($row['type'] === 'blend') {
                BlendOrder::create([
                    'order_id' => $order->id,
                    'kit_id' => $kit,
                    'quantity' => $row['quantity'],
                    'unit_id' => $unit,
                    'is_remote_pick' => $row['is_remote_pick'],
                ]);
            } else if($row['type'] === 'production') {
                 ProductionOrder::create([
                    'order_id' => $order->id,
                    'kit_id' => $kit,
                    'quantity' => $row['quantity'],
                    'unit_id' => $unit,
                    'is_remote_pick' => $row['is_remote_pick'] ? $row['is_remote_pick'] : 0,
                    'is_allergen_pick' => $row['is_allergen_pick'] ? $row['is_allergen_pick'] : 0,
                ]);
            } else if($row['type'] == 'receiving') {
                 ReceivingOrder::create([
                    'order_id' =>  $order->id,
                    'shipper_id' => $shipper,
                    'receive_form' => '',
                    'quantity' => $row['quantity'],
                    'unit_id' => $unit,
                ]); 
            } else {
                 ShippingOrder::create([
                    'order_id' => $order->id,
                    'shipper_id' => $shipper,
                    'ship_to_id' => $ship_to,
                    'stack_type_id' => $stack_type,
                    'charge_type_id' => $charge_type,
                    'is_remote_pick' => $row['is_remote_pick'] ? $row['is_remote_pick'] : 0,
                    'is_allergen_pick' => $row['is_allergen_pick'] ? $row['is_allergen_pick'] : 0,
                    'is_customer_called' => $row['is_customer_called'] ? $row['is_customer_called'] : 0,
                ]);
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 10;
    }

    public function rules(): array
    {
        return [
            "customer_code" => "required|exists:customers,code",
            "type" => "required|in:blend,production,shipping,receiving",
            "date" => "required",
            "time" => "required",
            "po_number" => "nullable|integer",
            "release_number" => "nullable|integer",
            "po_notes" => "nullable|string",
            "notes" => "nullable|string",
            "quantity" => "nullable|integer",
            
            "driver1" => "required|exists:drivers,name",
            "driver2" => "required|exists:drivers,name",
            "kit_name" => "nullable|exists:kits,name",
            "unit_name" => "nullable|exists:units,name", 
            "stack_type_name" => "nullable|exists:stack_types,name",
            "charge_type_name" => "nullable|exists:charge_types,name",
            
            "shipper_name" => "nullable|exists:shippers,shipper_name",
            "ship_to_name" => "nullable|exists:ship_tos,name",
           
            "is_remote_pick" => "nullable|in:0,1",
            "is_allergen_pick" => "nullable|in:0,1",
            "is_customer_called" => "nullable|in:0,1",
            "receive_from" => "nullable|exists:customers,name",
        ];
    }

    public static function afterImport(AfterImport $event)
    {
        $user = auth()->user();
        $import = "Import is done";
        Mail::to(auth()->user()->email)->send( new ImportOrderMail($user->email));
    }

    public function failure(Failure ...$failure)
    {
        foreach ($failure as $fail) {
            $fail->row(); 
            $fail->attribute();
            $fail->errors(); 
            $fail->values(); 
        }
        // $this->afterImport($fail);
    }

}
