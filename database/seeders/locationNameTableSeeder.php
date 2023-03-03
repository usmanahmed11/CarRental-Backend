<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class locationNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locationName = [
            [
                'location_name' => 'Lahore C1'
            ],
            [
                'location_name' => 'Lahore C2'
            ],
            [
                'location_name' => 'Lahore C3'
            ],
            [
                'location_name' => 'Lahore C4'
            ],
            [
                'location_name' => 'Islamabad'
            ],
           
        ];

        foreach ($locationName as $locationName) {
            DB::table('location')->insert($locationName);
        }
    }
}
