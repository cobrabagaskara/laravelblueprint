<?php

namespace Database\Factories;

use App\Models\AdSlot;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdSlotFactory extends Factory
{
    protected $model = AdSlot::class;

    public function definition(): array
    {
        $name = fake()->randomElement(['Header Banner', 'Sidebar 300x250', 'In-Article 728x90', 'Footer Banner']);
        $position = fake()->randomElement(['header', 'sidebar', 'in-article', 'footer']);
        $size = fake()->randomElement(['300x250', '728x90', '160x600', '320x50']);
        [$width, $height] = explode('x', $size);

        return [
            'name' => $name,
            'code' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'position' => $position,
            'html_content' => null,
            'image_url' => null,
            'link_url' => null,
            'width' => $width,
            'height' => $height,
            'is_active' => true,
            'starts_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'ends_at' => fake()->optional()->dateTimeBetween('now', '+3 months'),
            'impressions' => fake()->numberBetween(0, 100000),
            'clicks' => fake()->numberBetween(0, 5000),
        ];
    }
}