<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'naam' => fake()->sentence(3), // Een korte zin als naam
            'datum' => fake()->dateTimeBetween('now', '+1 year'), // Datum in de toekomst
            'prijsVolwassenen' => fake()->randomFloat(2, 10, 100), // Prijs tussen 10.00 en 100.00
            'prijsKinderen' => fake()->randomFloat(2, 5, 50),      // Prijs tussen 5.00 en 50.00
            'beschrijving' => fake()->paragraph(), // Een alinea tekst
            'locatie' => fake()->address(), // Een nep adres
            'tijdsduur' => fake()->numberBetween(60, 240), // Tussen 1 en 4 uur (in minuten)
            'maxDeelnemers' => fake()->numberBetween(5, 30), // Tussen 5 en 30 deelnemers
        ];
    }
}
