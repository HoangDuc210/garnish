<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            UserAdminDefaultSeeder::class,
            UnitSeeder::class,
            CompanySeeder::class,

            // comment out this for production
            TestDatabaseSeeder::class,
        ]);
    }
}
