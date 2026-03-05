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
            // Seed dependencies first
            MeasurementUnitSeeder::class,
            TablePictureSeeder::class,
            StateSeeder::class,
            ThemeSeeder::class,
            AllergySeeder::class,
            DecorationSeeder::class,

            // Core domain data
            IngredientSeeder::class,
            PriceEvolutionSeeder::class, // Added this seeder
            AllergySeeder::class,
            DessertSeeder::class,
            WorkshopSeeder::class,

            // Then seed items that depend on the above
            ReviewSeeder::class,
            SurplusSeeder::class,
            OrderSeeder::class,
            ShoppinglistSeeder::class,
            // ShoppinglistItemSeeder::class, // Table does not exist yet
        ]);
    }
}
