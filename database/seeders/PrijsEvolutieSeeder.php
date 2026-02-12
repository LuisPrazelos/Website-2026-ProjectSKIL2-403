<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PrijsEvolutie;

class PrijsEvolutieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Genereer 20 extra willekeurige prijs evoluties
        PrijsEvolutie::factory(20)->create();
    }
}
