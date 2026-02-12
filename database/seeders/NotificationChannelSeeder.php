<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationChannel;

class NotificationChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create three channels in one factory call using a sequence
        NotificationChannel::factory()
            ->count(3)
            ->sequence(
                ['name' => 'email'],
                ['name' => 'sms'],
                ['name' => 'push']
            )
            ->create();
    }
}
