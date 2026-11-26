<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'news_id' => News::inRandomOrder()->first()?->id ?? News::factory(),
            'user_id' => User::inRandomOrder()->first()?->id,
            'author_name' => fake()->name(),
            'author_email' => fake()->safeEmail(),
            'content' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'likes_count' => fake()->numberBetween(0, 100),
            'dislikes_count' => fake()->numberBetween(0, 20),
            'parent_id' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => ['status' => 'approved']);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function replyTo(Comment $parent): static
    {
        return $this->state(fn () => [
            'news_id' => $parent->news_id,
            'parent_id' => $parent->id,
        ]);
    }
}