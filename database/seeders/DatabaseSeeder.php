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

        // Create an admin user
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone_number' => '9876543210', // Example phone number
            'password' => '1234',
            'role_id' => 2, // Admin role_id
            'is_active' => true,
        ]);

        // Seed the rest of the data in an order that satisfies foreign key dependencies.
        $this->call([
            // Seed dependencies first
            MeasurementUnitSeeder::class,
            TablePictureSeeder::class,
            StateSeeder::class,
            ThemeSeeder::class,
            AllergySeeder::class, // AllergySeeder moet voor IngredientAllergySeeder komen
            DecorationSeeder::class,

            // Core domain data
            CategorySeeder::class,
            IngredientSeeder::class, // IngredientSeeder moet voor IngredientAllergySeeder komen
            PriceEvolutionSeeder::class,
            DessertSeeder::class,
            WorkshopSeeder::class,
            HappeningSeeder::class, // Added HappeningSeeder
            ProposalSeeder::class, // Added ProposalSeeder
            IngredientAllergySeeder::class, // Nu wordt deze aangeroepen

            // Pivot table seeder for desserts and ingredients
            IngredientDessertSeeder::class,

            // Then seed items that depend on the above
            ReviewSeeder::class,
            SurplusSeeder::class,
            OrderSeeder::class,
            ShoppinglistSeeder::class,
            // ShoppinglistItemSeeder::class, // Table does not exist yet
        ]);
    }
}
