<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dessert;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TablePicture>
 */
class TablePictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dessert_id' => Dessert::factory(), // Creates a related Dessert if none exists
            'title' => $this->faker->sentence(3), // Generates a random title
            'hash' => $this->faker->unique()->sha256, // Generates a unique hash
        ];
    }
}
