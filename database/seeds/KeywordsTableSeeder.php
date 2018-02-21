<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeywordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('keywords')->insert([
            'name' => 'where',
        ]);
        DB::table('keywords')->insert([
            'name' => 'weather',
        ]);
        DB::table('keywords')->insert([
            'name' => 'who',
        ]);
        DB::table('keywords')->insert([
            'name' => 'what',
        ]);
    }
}
