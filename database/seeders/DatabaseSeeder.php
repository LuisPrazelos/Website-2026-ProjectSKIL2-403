<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure roles are seeded first so users can reference role_id = 1
        $this->call([
            RoleSeeder::class,
        ]);

        // Create a single test user
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone_number' => '0123456789',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'is_active' => true,
        ]);

        // Seed the rest of the data. ReviewSeeder must run after desserts and workshops
        // so foreign keys in reviews (desssertId/workshopId) reference existing rows.
        $this->call([
            AllergySeeder::class,
            MeasurementUnitSeeder::class,
            IngredientSeeder::class,
            DessertSeeder::class,
            WorkshopSeeder::class,
            ReviewSeeder::class,
            OrderSeeder::class,
            SurplusSeeder::class,
            ShoppinglistSeeder::class,
            ShoppinglistItemSeeder::class,
        ]);
    }
}
