<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceEvolution;

class PriceEvolutionSeeder extends Seeder
{
    public function run()
    {
        PriceEvolution::factory()->count(50)->create();
    }
}
