<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\jobTitleTableSeeder;
use Database\Seeders\locationNameTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\skillSetTableSeeder;
use Database\Seeders\statusTableSeeder;
use Database\Seeders\teamNameTableSeeder;
use Database\Seeders\UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(jobTitleTableSeeder::class);
        $this->call(locationNameTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(skillSetTableSeeder::class);
        $this->call(statusTableSeeder::class);
        $this->call(teamNameTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
