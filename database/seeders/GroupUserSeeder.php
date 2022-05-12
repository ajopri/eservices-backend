<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_user')->insert([
            ['user_id' => 1, 'group_id' => 1, 'card_code' => Str::random(6), 'access' => 1, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
