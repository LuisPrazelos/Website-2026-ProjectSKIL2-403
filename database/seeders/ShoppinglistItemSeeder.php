<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShoppinglistItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shoppinglist_items')->insert([
            [
                'shoppinglistId' => 1,
                'ingredientId' => 1,
                'quantity' => 2,
                'unit' => 'pcs',
                'isChecked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shoppinglistId' => 1,
                'ingredientId' => 2,
                'quantity' => 1,
                'unit' => 'loaf',
                'isChecked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
