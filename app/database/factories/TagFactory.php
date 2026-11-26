<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Startup', 'UMKM', 'AI', 'Timnas', 'PialaDunia2026',
            'Pemilu2026', 'Kripto', 'Energi', 'Kesehatan', 'Pendidikan',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'color' => fake()->randomElement(['primary', 'success', 'warning', 'danger', 'info', 'gray']),
        ];
    }
}