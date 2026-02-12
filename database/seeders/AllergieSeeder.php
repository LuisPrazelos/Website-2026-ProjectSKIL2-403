<?php

namespace Database\Seeders;

use App\Models\Allergie;
use Illuminate\Database\Seeder;

class AllergieSeeder extends Seeder
{
    public function run(): void
    {
        Allergie::factory(5)->create();
    }
}
