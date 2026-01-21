<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'avatar_path' => null,
            'provider' => null,
            'provider_id' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withOAuth(string $provider = 'google'): static
    {
        return $this->state(fn (array $attributes) => [
            'password' => null,
            'provider' => $provider,
            'provider_id' => fake()->uuid(),
        ]);
    }

    public function withAvatar(): static
    {
        return $this->state(fn (array $attributes) => [
            'avatar_path' => 'avatars/'.fake()->uuid().'.jpg',
        ]);
    }
}
