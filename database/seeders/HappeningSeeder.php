<?php

namespace Database\Seeders;

use App\Models\Happening;
use Illuminate\Database\Seeder;

class HappeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Happening::factory()
            ->count(10)
            ->create();
    }
}
