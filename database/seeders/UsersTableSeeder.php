<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'Admin')->value('id');
        User::create([
            'name' => 'Admin',
            'email' => 'usman.ahmed@nxb.com.pk',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'active',
            'role_id' =>  $adminRoleId,
        ]);
    }
}
