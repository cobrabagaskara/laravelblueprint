<?php

namespace Database\Factories;

use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence(10) . '?',
            'description' => fake()->sentence(15),
            'is_active' => true,
            'allow_multiple' => fake()->boolean(20),
            'total_votes' => 0,
            'starts_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'ends_at' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}