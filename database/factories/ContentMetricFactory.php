<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\ContentMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentMetricFactory extends Factory
{
    protected $model = ContentMetric::class;

    public function definition(): array
    {
        return [
            'content_id' => Content::factory(),
            'views' => fake()->numberBetween(0, 1000),
            'downloads' => fake()->numberBetween(0, 500),
            'date' => fake()->date(),
        ];
    }
}
