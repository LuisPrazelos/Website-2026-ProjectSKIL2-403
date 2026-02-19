<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkshopUser;
use App\Models\Workshop;
use App\Models\User;

class WorkshopUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are workshops and users to link
        $workshopIds = Workshop::pluck('workshopId')->all();
        $userIds = User::pluck('id')->all();

        if (empty($workshopIds) || empty($userIds)) {
            // Nothing to link; skip seeding
            return;
        }

        // Create 10 workshop-user registrations
        WorkshopUser::factory()->count(10)->make()->each(function (WorkshopUser $wu) use ($workshopIds, $userIds) {
            $wu->workshop_id = $workshopIds[array_rand($workshopIds)];
            $wu->user_id = $userIds[array_rand($userIds)];
            $wu->save();
        });
    }
}

