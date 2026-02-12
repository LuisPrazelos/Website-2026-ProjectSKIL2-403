<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShoppinglistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shoppinglists')->insert([
            'personId' => 1, // must exist in people
            'name' => 'Weekly groceries',
            'isCompleted' => false,
            'internalComment' => 'For the weekend',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
