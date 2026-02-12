<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'personId' => Person::factory(),

            'orderDate' => $this->faker->date(),

            // nullable foreign keys
            'availability' => $this->faker->boolean(70)
                ? Availability::factory()
                : null,

            'requestProposalId' => $this->faker->boolean(50)
                ? RequestProposal::factory()
                : null,

            'decorationId' => $this->faker->boolean(50)
                ? Decoration::factory()
                : null,

            // fields
            'deliveryAddress' => $this->faker->optional()->address(),

            'isProposal' => $this->faker->boolean(),
            'isOrdered' => $this->faker->boolean(),
            'isPrepared' => $this->faker->boolean(),
            'isPickedUp' => $this->faker->boolean(),
            'isCancelled' => $this->faker->boolean(),
            'hasToBeDelivered' => $this->faker->boolean(),

            'internalComment' => $this->faker->optional()->sentence(),
        ];
    }
}
