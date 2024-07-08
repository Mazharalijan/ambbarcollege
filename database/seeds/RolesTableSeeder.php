<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Super Administrator / Owner',
                'slug' => 'super-admin',
            ],
            [
                'name' => 'Sub admin',
                'slug' => 'sub-admin',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
            ],
        ];
        \DB::table('roles')->insert($roles);

    }
}
