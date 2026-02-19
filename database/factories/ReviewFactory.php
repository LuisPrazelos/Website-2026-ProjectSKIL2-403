<?php

namespace Database\Factories;

use App\Models\review;
use App\Models\User;
use App\Models\Dessert;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\review>
 */
class ReviewFactory extends Factory
{
    protected $model = review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => fake()->numberBetween(1, 10),
            'content' => fake()->paragraph(),
            'date' => fake()->date(),
            'userId' => User::factory(),

            // Use an existing dessert ID when available, otherwise create one.
            'desssertId' => function () {
                $id = Dessert::query()->inRandomOrder()->value('id');
                return $id ?: Dessert::factory();
            },

            // Use an existing workshop ID when available, otherwise create one.
            'workshopId' => function () {
                $id = Workshop::query()->inRandomOrder()->value('workshopId');
                return $id ?: Workshop::factory();
            },
        ];
    }
}
