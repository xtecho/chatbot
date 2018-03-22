<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadwordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keywords')->insert([
            'word' => 'fuck',
        ]);
        DB::table('keywords')->insert([
            'word' => 'sex',
        ]);
        DB::table('keywords')->insert([
            'word' => 'xxx',
        ]);
    }
}
