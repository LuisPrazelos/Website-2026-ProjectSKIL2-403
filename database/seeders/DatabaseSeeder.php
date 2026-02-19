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

        // Seed the rest of the data in an order that satisfies foreign key dependencies.
        $this->call([
            // Basic reference data
            MeasurementUnitSeeder::class,
            StateSeeder::class,
            ThemeSeeder::class,
            AllergySeeder::class,
            DecorationSeeder::class,

            // Core domain data
            IngredientSeeder::class,
            DessertSeeder::class,
            IngredientDessertSeeder::class,
            PriceEvolutionSeeder::class,

            // Workshops/happenings
            WorkshopSeeder::class,
            HappeningSeeder::class,
            WorkshopUserSeeder::class,

            // Reviews depend on users + desserts/workshops
            ReviewSeeder::class,

            // Orders and order items
            OrderSeeder::class,
            OrderItemSeeder::class,

            // Surpluses (depend on desserts)
            SurplusSeeder::class,

            // Notifications (categories/channels before preferences)
            NotificationCategorySeeder::class,
            NotificationChannelSeeder::class,
            NotificationPreferenceSeeder::class,

            // Shopping lists and items (shopping lists before items)
            ShoppinglistSeeder::class,
            ShoppinglistItemSeeder::class,
        ]);
    }
}
