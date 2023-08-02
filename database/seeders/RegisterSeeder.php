<?php

namespace Database\Seeders;

use App\Models\BillingContact;
use App\Models\ProvisionAccount;
use App\Models\Subscription;
use App\Models\SubscriptionDetail;
use App\Models\SubscriptionHistory;
use App\Models\Tenant;
use App\Models\Tenant\Role;
use App\Models\User;
use App\Models\Tenant\User as CUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class RegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'phone' => '+923333488588',
            'role' => 'super-admin',
        ]);
    }
}

