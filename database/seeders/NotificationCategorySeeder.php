<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationCategory;

class NotificationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create three categories in one factory call using a sequence
        NotificationCategory::factory()
            ->count(3)
            ->sequence(
                ['name' => 'comments'],
                ['name' => 'likes'],
                ['name' => 'follows']
            )
            ->create();
    }
}
