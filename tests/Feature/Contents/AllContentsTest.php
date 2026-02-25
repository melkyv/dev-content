<?php

use App\Livewire\Contents\Index;
use App\Models\Content;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;

it('renders the contents page', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertOk()
        ->assertSee('Explorar Conteúdos');
});

it('requires authentication', function () {
    $this->get(route('contents.index'))
        ->assertRedirect(route('login'));
});

it('displays all published contents from all users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Meu Conteúdo',
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo de Outro',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('Meu Conteúdo')
        ->assertSee('Conteúdo de Outro');
});

it('does not display draft contents', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'ConteúdoPublicado',
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Rascunho',
        'published_at' => null,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('ConteúdoPublicado')
        ->assertDontSee('Conteúdo Rascunho');
});

it('does not display scheduled contents', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Publicado',
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Agendado',
        'published_at' => now()->addDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('Conteúdo Publicado')
        ->assertDontSee('Conteúdo Agendado');
});

it('filters contents by search', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Laravel Tutorial',
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Vue.js Guide',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('search', 'Laravel')
        ->assertSee('Laravel Tutorial')
        ->assertDontSee('Vue.js Guide');
});

it('filters contents by type premium', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Gratuito',
        'is_premium' => false,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('type', 'premium')
        ->assertSee('Conteúdo Premium')
        ->assertDontSee('Conteúdo Gratuito');
});

it('filters contents by type free', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Gratuito',
        'is_premium' => false,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('type', 'free')
        ->assertSee('Conteúdo Gratuito')
        ->assertDontSee('Conteúdo Premium');
});

it('sorts contents by views', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('sortBy', 'views')
        ->assertSee('Conteúdo Teste');
});

it('sorts contents by downloads', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('sortBy', 'downloads')
        ->assertSee('Conteúdo Teste');
});

it('paginates contents', function () {
    $user = User::factory()->create();

    Content::factory()->count(15)->create([
        'user_id' => $user->id,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertViewHas('contents', function ($contents) {
            return $contents->count() === 10;
        });
});

it('resets filters', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->set('search', 'Teste')
        ->set('type', 'premium')
        ->set('sortBy', 'views')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('type', '')
        ->assertSet('sortBy', 'recent');
});

it('shows link to free content', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $content = Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo Gratuito',
        'is_premium' => false,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSeeHtml('href="'.route('contents.show', $content->id).'"');
});

it('shows link to premium content for subscriber', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $plan = Plan::factory()->create();
    Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'stripe_subscription_id' => 'sub_test',
        'status' => 'active',
        'amount' => 1000,
        'ends_at' => now()->addMonth(),
    ]);

    $content = Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSeeHtml('href="'.route('contents.show', $content->id).'"')
        ->assertSee('Premium');
});

it('shows subscription link for premium content without subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $content = Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('Premium')
        ->assertSeeHtml('href="'.route('subscription').'"')
        ->assertSee('Ver planos')
        ->assertDontSeeHtml('href="'.route('contents.show', $content->id).'"');
});

it('displays author name', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo de Outro',
        'published_at' => now()->subDay(),
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee($otherUser->name);
});

it('displays total files for premium content without subscription', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $content = Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
        'published_at' => now()->subDay(),
    ]);

    $content->files()->create([
        'original_name' => 'test.pdf',
        'path' => 'test.pdf',
        'mime_type' => 'application/pdf',
        'size' => 1024,
    ]);

    Livewire::actingAs($user)
        ->test(Index::class)
        ->assertSee('1 arquivo(s)');
});
