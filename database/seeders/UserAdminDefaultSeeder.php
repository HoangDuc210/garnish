<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::withoutEvents(function () {
            User::create([
                'username'          => 'admin',
                'name'              => 'Admin POS System',
                'email'             => 'admin@vaixgroup.com',
                'password'          => Hash::make('123456a@'),
                'role'              => SUPER_ADMIN,
                'email_verified_at' => now(),
            ]);
        });
    }
}
