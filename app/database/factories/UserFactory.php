<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => fake()->randomElement(['admin', 'editor', 'author']),
            'bio' => fake()->sentence(15),
            'avatar' => null,
            'social_twitter' => fake()->optional()->url(),
            'social_linkedin' => fake()->optional()->url(),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }

    public function editor(): static
    {
        return $this->state(fn () => ['role' => 'editor']);
    }

    public function author(): static
    {
        return $this->state(fn () => ['role' => 'author']);
    }
}