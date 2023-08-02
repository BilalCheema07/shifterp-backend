<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = array(
            [
                "name" => "Quinton Cornia"
            ],
            [
                "name" => "Andrew Piper"
            ],
            [
                "name" => "Andrew Coram"
            ],
            [
                "name" => "Thomas Hudson"
            ],
            [
                "name" => "David Tovar"
            ],
            [
                "name" => "Ralph Castro"
            ],
            [
                "name" => "Timothy Zabala"
            ],
        );

        foreach ($drivers as $driver) {
			Driver::create($driver);
		}
    }
}
