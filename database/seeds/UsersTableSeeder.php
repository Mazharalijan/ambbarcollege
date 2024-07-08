<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'super',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'superadmin11@admin.com',
                'password' => \Illuminate\Support\Facades\Hash::make('Admin1122'),
                'status' => 'active',
                'role_id' => '1'
            ],
            [
                'username' => 'admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin'),
                'status' => 'active',
                'role_id' => '1'
            ],
        ];
        \DB::table('users')->insert($users);

    }
}
