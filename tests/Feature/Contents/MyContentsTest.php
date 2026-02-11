<?php

use App\Livewire\Contents\MyContents;
use App\Models\Content;
use App\Models\User;
use Livewire\Livewire;

it('renders the my contents page', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->assertOk()
        ->assertSee('Meus Conteúdos');
});

it('requires authentication', function () {
    $this->get(route('contents.my'))
        ->assertRedirect(route('login'));
});

it('displays user contents', function () {
    $user = User::factory()->create();
    $content = Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Meu Conteúdo Teste',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->assertSee('Meu Conteúdo Teste');
});

it('does not display other users contents', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Meu Conteúdo',
    ]);

    Content::factory()->create([
        'user_id' => $otherUser->id,
        'title' => 'Conteúdo de Outro',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->assertSee('Meu Conteúdo')
        ->assertDontSee('Conteúdo de Outro');
});

it('filters contents by search', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Laravel Tutorial',
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Vue.js Guide',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('search', 'Laravel')
        ->assertSee('Laravel Tutorial')
        ->assertDontSee('Vue.js Guide');
});

it('filters contents by status', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Publicado',
        'published_at' => now()->subDay(),
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Rascunho',
        'published_at' => null,
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('status', 'published')
        ->assertSee('Conteúdo Publicado')
        ->assertDontSee('Conteúdo Rascunho');
});

it('filters contents by type', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
    ]);

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Gratuito',
        'is_premium' => false,
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('type', 'premium')
        ->assertSee('Conteúdo Premium')
        ->assertDontSee('Conteúdo Gratuito');
});

it('sorts contents by views', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('sortBy', 'views')
        ->assertSee('Conteúdo Teste');
});

it('sorts contents by downloads', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('sortBy', 'downloads')
        ->assertSee('Conteúdo Teste');
});

it('paginates contents', function () {
    $user = User::factory()->create();

    Content::factory()->count(15)->create([
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->assertViewHas('contents', function ($contents) {
            return $contents->count() === 10;
        });
});

it('resets filters', function () {
    $user = User::factory()->create();

    Content::factory()->create([
        'user_id' => $user->id,
        'title' => 'Conteúdo Teste',
    ]);

    Livewire::actingAs($user)
        ->test(MyContents::class)
        ->set('search', 'Teste')
        ->set('status', 'published')
        ->set('type', 'premium')
        ->set('sortBy', 'views')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('status', '')
        ->assertSet('type', '')
        ->assertSet('sortBy', 'recent');
});
