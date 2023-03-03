<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class teamNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teamName = [
            [
                'team_name' => 'App Dev'
            ],
            [
                'team_name' => 'Web Dev'
            ],
           
        ];

        foreach ($teamName as $teamName) {
            DB::table('team')->insert($teamName);
        }
    }
}
