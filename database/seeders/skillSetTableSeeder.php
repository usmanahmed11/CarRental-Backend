<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class skillSetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skillSet = [
            [
                'skill_name' => 'PHP'
            ],
            [
                'skill_name' => 'Laravel'
            ],
            [
                'skill_name' => 'React Js'
            ],
        ];

        foreach ($skillSet as $skillSet) {
            DB::table('skillSet')->insert($skillSet);
        }
    }
}
