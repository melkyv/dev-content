<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content_id' => Content::factory(),
            'path' => 'uploads/'.fake()->uuid().'.'.fake()->fileExtension(),
            'original_name' => fake()->word().'.'.fake()->fileExtension(),
            'mime_type' => fake()->mimeType(),
            'size' => fake()->numberBetween(1024, 10485760),
            'disk' => fake()->randomElement(['local', 's3']),
            'is_processed' => fake()->boolean(),
        ];
    }

    public function processed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_processed' => true,
        ]);
    }

    public function unprocessed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_processed' => false,
        ]);
    }

    public function localDisk(): static
    {
        return $this->state(fn (array $attributes) => [
            'disk' => 'local',
        ]);
    }

    public function s3Disk(): static
    {
        return $this->state(fn (array $attributes) => [
            'disk' => 's3',
        ]);
    }
}
