<?php

namespace Database\Factories;

use App\Models\LiveReport;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveReportFactory extends Factory
{
    protected $model = LiveReport::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'content' => fake()->paragraph(),
            'timestamp' => fake()->dateTimeBetween('-1 day', 'now'),
            'news_id' => News::inRandomOrder()->first()?->id,
            'is_published' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}