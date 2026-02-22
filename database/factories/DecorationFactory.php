<?php

namespace Database\Factories;

use App\Models\Decoration;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

class DecorationFactory extends Factory
{
    protected $model = Decoration::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(2, true)),
            'price' => fake()->randomFloat(2, 5, 250),
            'content' => fake()->optional()->sentence(),

            // theme is optional (0..1)
            'themeId' => fake()->boolean(70)
                ? Theme::factory()
                : null,
        ];
    }
}
