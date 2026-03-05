<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dessert;
use App\Models\Picture; // Import the Picture model

class DessertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing desserts to prevent duplicates on re-seeding
        Dessert::query()->delete();

        // Create a placeholder picture record
        $placeholderPicture = Picture::firstOrCreate(
            ['hash' => 'Gebakjes.jpg'], // Use a unique identifier for the placeholder
            [
                'title' => 'Placeholder Dessert Image',
                'hash' => 'Gebakjes.jpg', // This should be the path or a unique hash
            ]
        );

        // Create a few specific desserts with the placeholder picture
        Dessert::factory()->create([
            'name' => 'Appeltaart',
            'price' => 3.50,
            'description' => 'Heerlijke Hollandse appeltaart met kaneel.',
            'preparation_method' => 'Gebakken',
            'notes' => 'Populair',
            'picture_id' => $placeholderPicture->id,
            'portion_size' => 1.0,
            'measurement_unit_id' => 1,
        ]);

        Dessert::factory()->create([
            'name' => 'Chocolademousse',
            'price' => 4.25,
            'description' => 'Luchtige chocolademousse van pure chocolade.',
            'preparation_method' => 'Gekoeld',
            'notes' => 'Rijk van smaak',
            'picture_id' => $placeholderPicture->id,
            'portion_size' => 1.0,
            'measurement_unit_id' => 1,
        ]);

        Dessert::factory()->create([
            'name' => 'Tiramisu',
            'price' => 5.00,
            'description' => 'Klassieke Italiaanse tiramisu met mascarpone en koffie.',
            'preparation_method' => 'Gekoeld',
            'notes' => 'Authentiek recept',
            'picture_id' => $placeholderPicture->id,
            'portion_size' => 1.0,
            'measurement_unit_id' => 1,
        ]);

        // Create 7 more random desserts using the factory, all with the placeholder picture
        Dessert::factory()->count(7)->create([
            'picture_id' => $placeholderPicture->id,
        ]);
    }
}
