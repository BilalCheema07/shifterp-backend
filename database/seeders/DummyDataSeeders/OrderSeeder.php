<?php

namespace Database\Seeders\DummyDataSeeders;

use App\Models\Tenant\{BlendOrder, ChargeType, Kit, Order, ProductionOrder, ReceivingOrder, Shipper, ShippingOrder, ShipTo, StackType, Unit};
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the TenantDataSeeders.
     *
     * @return void
     */
    public function run()
    {
        foreach (Order::factory()->count(10)->create() as $order) {
            
            $kit = Kit::query()->inRandomOrder()->first();
            $unit = Unit::query()->inRandomOrder()->first();
            $shipper = Shipper::query()->inRandomOrder()->first();
            $ship_to = ShipTo::query()->inRandomOrder()->first();
            $charge_type = ChargeType::query()->inRandomOrder()->first();
            $stack_type = StackType::query()->inRandomOrder()->first();

            switch ($order->type) {
                case 'blend':
                    BlendOrder::create([
                        'order_id' => $order->id,
                        'kit_id' => $kit->id,
                        'quantity' => rand(000, 999),
                        'unit_id' => $unit->id,
                        'is_remote_pick' => rand(0, 1),
                    ]);
                    break;
                case 'production':
                    ProductionOrder::create([
                        'order_id' => $order->id,
                        'kit_id' => $kit->id,
                        'quantity' => rand(000, 999),
                        'unit_id' => $unit->id,
                        'is_remote_pick' => rand(0, 1),
                        'is_allergen_pick' => rand(0, 1),
                    ]);
                    break;
                case 'shipping':
                    ShippingOrder::create([
                        'order_id' => $order->id,
                        'shipper_id' => $shipper->id,
                        'ship_to_id' => $ship_to->id,
                        'stack_type_id' => $stack_type->id,
                        'charge_type_id' => $charge_type->id,
                        'is_remote_pick' => rand(0, 1),
                        'is_allergen_pick' => rand(0, 1),
                        'is_customer_called' => rand(0, 1),
                    ]);
                    break;
                default:
                    ReceivingOrder::create([
                        'order_id' =>  $order->id,
                        'shipper_id' => $shipper->id,
                        'receive_form' => '',
                        'quantity' => rand(000, 999),
                        'unit_id' => $unit->id,
                    ]); 
                    break;
            }
        }
    }
}
