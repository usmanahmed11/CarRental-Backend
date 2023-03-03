<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'Admin')->value('id');
        $recruitmentManagerRoleId = DB::table('roles')->where('role_name', 'Recruitment manager')->value('id');
        $userRoleId = DB::table('roles')->where('role_name', 'User')->value('id');

        DB::table('users')->where('id', 1)->update(['role_id' => $adminRoleId]);
        DB::table('users')->where('id', 2)->update(['role_id' => $recruitmentManagerRoleId]);
        DB::table('users')->where('id', '>', 2)->update(['role_id' => $userRoleId]);
    }
}
