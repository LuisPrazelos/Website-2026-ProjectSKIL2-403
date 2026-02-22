<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Surplus;
use App\Models\Dessert;

class SurplusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there's a dessert to link to (DessertSeeder will create one if needed)
        $dessert = Dessert::first();
        if (! $dessert) {
            $this->call(DessertSeeder::class);
            $dessert = Dessert::first();
        }

        // Create a single explicit surplus record (similar style to your User example)
        Surplus::factory()->create([
            'date' => now()->toDateString(),
            'total_amount' => 12,
            'sale' => 3.50,
            'status' => 'available',
            'expiration_date' => now()->addDays(7)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record',
        ]);
    }
}
