<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jobTitleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobTitle = [
            [
                'job_title' => 'Software Engineer'
            ],
            [
                'job_title' => 'Senior Software Engineer'
            ],
            [
                'job_title' => 'Principal Software Engineer'
            ],
        ];

        foreach ($jobTitle as $jobTitle) {
            DB::table('jobTitle')->insert($jobTitle);
        }
    }
}
