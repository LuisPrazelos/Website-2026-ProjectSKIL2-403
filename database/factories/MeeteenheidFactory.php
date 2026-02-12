<?php

namespace Database\Factories;

use App\Models\Meeteenheid;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeeteenheidFactory extends Factory
{
    protected $model = Meeteenheid::class;

    public function definition(): array
    {
        return [
            'eenheidNaam' => fake()->word(),
        ];
    }
}
