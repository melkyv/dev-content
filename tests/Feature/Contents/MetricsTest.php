<?php

use App\Models\Content;
use App\Models\ContentMetric;
use App\Models\User;

it('records view when accessing content', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create([
        'published_at' => now()->subDay(),
    ]);

    $this->actingAs($user)->get(route('contents.show', $content->id));

    $metric = ContentMetric::where('content_id', $content->id)->first();
    expect($metric)->not->toBeNull();
    expect($metric->views)->toBe(1);
});

it('does not record view for premium content without subscription', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create([
        'published_at' => now()->subDay(),
        'is_premium' => true,
    ]);

    $this->actingAs($user)->get(route('contents.show', $content->id))
        ->assertRedirect(route('contents.my'));

    expect(ContentMetric::where('content_id', $content->id)->count())->toBe(0);
});

it('records view for premium content with subscription', function () {
    $plan = \App\Models\Plan::factory()->create();
    $user = User::factory()->create();
    $user->subscription()->create([
        'plan_id' => $plan->id,
        'stripe_subscription_id' => 'sub_test',
        'status' => 'active',
        'amount' => 1000,
        'ends_at' => now()->addMonth(),
    ]);

    $content = Content::factory()->create([
        'published_at' => now()->subDay(),
        'is_premium' => true,
    ]);

    $this->actingAs($user)->get(route('contents.show', $content->id));

    expect(ContentMetric::where('content_id', $content->id)->first())->views->toBe(1);
});
