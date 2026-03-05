<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();

        $categories = [
            ['name' => 'Voorgerecht'],
            ['name' => 'Hoofdgerecht'],
            ['name' => 'Dessert'],
            ['name' => 'Soep'],
            ['name' => 'Salade'],
            ['name' => 'Drankje'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
