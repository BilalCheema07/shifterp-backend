<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $subscriptions = array(
            [
                'name' => 'Basic License',
                'user_limit' => 15,
                'facility_limit' => 5,
                'price_per_license' => 100,
            ],
            [
                'name' => 'Enterprise License',
                'user_limit' => 150,
                'facility_limit' => 20,
                'price_per_license' => 800,
        
            ]
        );

        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}
