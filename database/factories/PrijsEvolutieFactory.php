<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrijsEvolutie>
 */
class PrijsEvolutieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // We genereren een willekeurig ID voor ingredientId.
            // In een echte app zou je hier een bestaand Ingredient ID willen gebruiken.
            'ingredientId' => fake()->numberBetween(1, 50),
            'prijs' => fake()->randomFloat(2, 0.50, 20.00), // Prijs tussen 0.50 en 20.00
            'hoeveelheid' => fake()->randomFloat(2, 0.1, 5.0), // Hoeveelheid (bijv. kg of liter)
            'datum' => fake()->dateTimeBetween('-1 year', 'now'), // Datum in het afgelopen jaar
            'bron' => fake()->company(), // Naam van een winkel of leverancier
        ];
    }
}
