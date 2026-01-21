<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Basic', 'Pro', 'Enterprise', 'Premium']);

        return [
            'name' => $name,
            'slug' => strtolower($name),
            'price' => fake()->numberBetween(0, 10000),
            'stripe_price_id' => fake()->optional()->uuid(),
            'is_active' => true,
        ];
    }

    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'stripe_price_id' => null,
        ]);
    }

    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 1990,
            'stripe_price_id' => 'price_'.fake()->uuid(),
        ]);
    }
}
