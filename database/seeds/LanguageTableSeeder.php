<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'language' => 'English',
            ],
            [
                'language' => 'Arabic',
            ]
        ];
        \DB::table('languages')->insert($languages);
    }
}
