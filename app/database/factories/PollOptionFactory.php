<?php

namespace Database\Factories;

use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollOptionFactory extends Factory
{
    protected $model = PollOption::class;

    public function definition(): array
    {
        return [
            'poll_id' => Poll::factory(),
            'label' => fake()->randomElement(['Setuju', 'Tidak Setuju', 'Netral', 'Ragu-ragu']),
            'vote_count' => 0,
            'sort_order' => fake()->numberBetween(0, 5),
        ];
    }
}