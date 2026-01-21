<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'stripe_subscription_id' => 'sub_'.fake()->uuid(),
            'status' => fake()->randomElement(['pending', 'active', 'canceled', 'past_due']),
            'ends_at' => fake()->optional()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'ends_at' => null,
        ]);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'canceled',
            'ends_at' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }

    public function pastDue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'past_due',
            'ends_at' => null,
        ]);
    }
}
