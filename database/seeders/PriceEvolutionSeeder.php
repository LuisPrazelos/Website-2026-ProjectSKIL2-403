<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceEvolution;
use Carbon\Carbon;

class PriceEvolutionSeeder extends Seeder
{
    public function run()
    {
        // Price evolution for 'Bloem' (assuming ingredientId = 1)
        for ($i = 0; $i < 12; $i++) {
            PriceEvolution::create([
                'ingredientId' => 1,
                'price' => 1.50 + ($i * 0.05), // Price increases over time
                'amount' => 1,
                'date' => Carbon::now()->subMonths($i),
                'source' => 'Supplier A',
            ]);
        }

        // Price evolution for 'Suiker' (assuming ingredientId = 2)
        for ($i = 0; $i < 12; $i++) {
            PriceEvolution::create([
                'ingredientId' => 2,
                'price' => 0.80 + ($i * 0.02), // Price increases over time
                'amount' => 1,
                'date' => Carbon::now()->subMonths($i),
                'source' => 'Supplier B',
            ]);
        }

        // Create 50 random price evolutions using the factory
        PriceEvolution::factory()->count(50)->create();
    }
}
