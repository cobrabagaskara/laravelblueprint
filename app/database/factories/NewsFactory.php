<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = fake()->sentence(8);
        $isPublished = fake()->boolean(80);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 99999),
            'excerpt' => fake()->sentence(20),
            'content' => collect(fake()->paragraphs(4))->map(fn ($p) => "<p>{$p}</p>")->implode(''),
            'image' => null,
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'user_id' => User::where('role', '!=', 'admin')->inRandomOrder()->first()?->id ?? User::factory(),
            'is_featured' => fake()->boolean(30),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'view_count' => fake()->numberBetween(0, 50000),
            'likes_count' => fake()->numberBetween(0, 2000),
            'dislikes_count' => fake()->numberBetween(0, 200),
            'meta_title' => $title,
            'meta_description' => fake()->sentence(15),
            'reading_time' => fake()->numberBetween(2, 10),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}