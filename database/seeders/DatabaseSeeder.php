<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // AdminUserSeeder::class,
            GroupSeeder::class,
            // UserGroupSeeder::class,
            RoleSeeder::class,
        ]);

        // \App\Models\User::factory(4)->create();
    }
}
