<?php

namespace Database\Factories;

use App\Models\Dessert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dessert>
 */
class DessertFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dessert::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'price' => fake()->randomFloat(2, 0, 50),
            'description' => fake()->optional()->sentence(),
            'preparation_method' => fake()->optional()->paragraph(),
            'notes' => fake()->optional()->sentence(),
            // image URL (optional) - you can store a path instead if you prefer
            'image' => fake()->optional()->imageUrl(640, 480),
            // portion size and measurement unit id (FK)
            'portion_size' => fake()->randomFloat(2, 0.1, 10),
            'measurement_unit_id' => 1,
        ];
    }
}
