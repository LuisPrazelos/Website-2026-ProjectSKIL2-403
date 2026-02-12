<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationPreference;
use App\Models\NotificationCategory;
use App\Models\NotificationChannel;
use App\Models\User;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use an existing user if present.
        $user = User::first();
        if (! $user) {
            // No user available to link preferences to; skip seeding preferences
            return;
        }

        // Ensure categories and channels exist
        $category = NotificationCategory::first() ?? NotificationCategory::factory()->create(['name' => 'comments']);
        $channel = NotificationChannel::first() ?? NotificationChannel::factory()->create(['name' => 'email']);

        // Create a preference linking user/category/channel
        NotificationPreference::factory()->create([
            'user_id' => $user->id,
            'notification_category_id' => $category->id,
            'notification_channel_id' => $channel->id,
            'received' => true,
        ]);
    }
}
