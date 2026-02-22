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
        if (!$dessert) {
            $this->call(DessertSeeder::class);
            $dessert = Dessert::first();
        }

        Surplus::factory()->create([
            'date' => now()->toDateString(),
            'total_amount' => 12,
            'sale' => 3.50,
            'expiration_date' => now()->addDays(7)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record',
        ]);
        Surplus::factory()->create([
            'date' => now()->subDays(1)->toDateString(),
            'total_amount' => 20,
            'sale' => 4.00,
            'expiration_date' => now()->addDays(5)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 2',
        ]);

        Surplus::factory()->create([
            'date' => now()->subDays(2)->toDateString(),
            'total_amount' => 15,
            'sale' => 2.75,
            'expiration_date' => now()->addDays(3)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 3',
        ]);

        Surplus::factory()->create([
            'date' => now()->subDays(3)->toDateString(),
            'total_amount' => 10,
            'sale' => 5.00,
            'expiration_date' => now()->addDays(10)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 4',
        ]);

        Surplus::factory()->create([
            'date' => now()->subDays(4)->toDateString(),
            'total_amount' => 25,
            'sale' => 3.25,
            'expiration_date' => now()->addDays(8)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 5',
        ]);

        Surplus::factory()->create([
            'date' => now()->subDays(5)->toDateString(),
            'total_amount' => 18,
            'sale' => 4.50,
            'expiration_date' => now()->addDays(6)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 6',
        ]);

        Surplus::factory()->create([
            'date' => now()->subDays(6)->toDateString(),
            'total_amount' => 30,
            'sale' => 6.00,
            'expiration_date' => now()->addDays(4)->toDateString(),
            'dessert_id' => $dessert->id,
            'comment' => 'Seeded surplus record 7',
        ]);
    }
}
