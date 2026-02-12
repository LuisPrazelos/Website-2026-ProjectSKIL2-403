<?php

namespace Database\Seeders;

use App\Models\Meeteenheid;
use Illuminate\Database\Seeder;

class MeeteenheidSeeder extends Seeder
{
    public function run(): void
    {
        Meeteenheid::factory(5)->create();
    }
}
