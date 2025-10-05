<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\Product;
use App\Models\ReceiptDetail;
use App\Models\User;
use App\Models\Deposit;
use App\Models\DepositDetail;
use App\Models\Receipt;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User
        User::factory()->count(40)->create();

        // Product
        Product::factory()->count(100)->create();

        // Agent
        Agent::factory()->count(5)->create();

        // Receipt
        Receipt::factory()->count(60)->create();

        // ReceiptDetail
        ReceiptDetail::factory()->count(90)->create();

        Deposit::factory()->count(50)->create();
        //Deposit detail
        // DepositDetail::factory()->count(60)->create();
    }
}
