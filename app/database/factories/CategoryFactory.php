<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Ekonomi', 'Olahraga', 'Teknologi', 'Hiburan',
            'Politik', 'Kesehatan', 'Pendidikan', 'Internasional',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->sentence(12),
            'icon' => fake()->randomElement(['heroicon-o-chart-bar', 'heroicon-o-trophy', 'heroicon-o-cpu-chip', 'heroicon-o-film', 'heroicon-o-building-library', 'heroicon-o-heart', 'heroicon-o-academic-cap', 'heroicon-o-globe-alt']),
            'color' => fake()->randomElement(['primary', 'success', 'warning', 'danger', 'info', 'gray']),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}