<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::firstOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'stripe_price_id' => null,
                'is_active' => true,
            ]
        );

        Plan::firstOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'price' => 4900,
                'stripe_price_id' => 'your_stripe_price_id',
                'is_active' => true,
            ]
        );
    }
}
