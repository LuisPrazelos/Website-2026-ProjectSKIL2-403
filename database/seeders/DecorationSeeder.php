<?php

namespace Database\Seeders;

use App\Models\Decoration;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DecorationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Decoration::factory()->count(10)->create();

    }
}
