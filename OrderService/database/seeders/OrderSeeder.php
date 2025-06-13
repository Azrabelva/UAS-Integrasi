<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'user_id'=>1,
            'product_id'=>1,
            'quantity'=>2,
            'total_price'=>240000,
        ]);
    }
}
