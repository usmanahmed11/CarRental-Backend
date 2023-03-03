<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class statusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            [
                'status' => 'Internee'
            ],
            [
                'status' => 'Probation'
            ],
            [
                'status' => 'Confirm'
            ],
            [
                'status' => 'Pending'
            ],
           
        ];

        foreach ($status as $status) {
            DB::table('status')->insert($status);
        }
    }
}
