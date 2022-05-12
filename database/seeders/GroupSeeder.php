<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            ['name' => 'NIPPON','card_code' => Str::random(6),'card_name' => Str::random(10), 'currency' => 'USD', 'cust_bu' => 'IS'],
        ]);
    }
}
