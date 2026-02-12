<?php

namespace Database\Seeders;

use App\Models\review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        review::factory(3)->create();
    }
}
