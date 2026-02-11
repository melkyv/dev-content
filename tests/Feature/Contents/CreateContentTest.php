<?php

use App\Livewire\Contents\Create;
use App\Models\Content;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the create content page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('contents.create'))
        ->assertOk()
        ->assertSee('Criar Novo Conteúdo');
});

it('requires authentication', function () {
    $this->get(route('contents.create'))
        ->assertRedirect(route('login'));
});

it('creates content successfully with publish now', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Meu Conteúdo')
        ->set('form.description', 'Uma descrição muito legal do meu conteúdo')
        ->set('form.publish_type', 'now')
        ->call('save')
        ->assertRedirect(route('contents.my'));

    $this->assertDatabaseHas('contents', [
        'user_id' => $user->id,
        'title' => 'Meu Conteúdo',
        'description' => 'Uma descrição muito legal do meu conteúdo',
        'is_premium' => false,
    ]);

    $content = Content::first();
    expect($content->published_at)->not->toBeNull();
});

it('creates content successfully with scheduled date', function () {
    $user = User::factory()->create();
    $scheduledDate = now()->addDay()->format('Y-m-d H:i:s');

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Conteúdo Agendado')
        ->set('form.description', 'Descrição do conteúdo agendado')
        ->set('form.publish_type', 'schedule')
        ->set('form.scheduled_date', $scheduledDate)
        ->call('save')
        ->assertRedirect(route('contents.my'));

    $this->assertDatabaseHas('contents', [
        'user_id' => $user->id,
        'title' => 'Conteúdo Agendado',
        'description' => 'Descrição do conteúdo agendado',
        'published_at' => $scheduledDate,
    ]);
});

it('creates content with files', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('document.pdf');

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Conteúdo com Arquivo')
        ->set('form.description', 'Descrição do conteúdo')
        ->set('form.publish_type', 'now')
        ->set('form.files', [$file])
        ->call('save')
        ->assertRedirect(route('contents.my'));

    $content = Content::first();
    expect($content->files)->toHaveCount(1);

    Storage::disk('public')->assertExists($content->files->first()->path);
});

it('allows premium user to create premium content', function () {
    $user = User::factory()->create();
    $plan = Plan::factory()->create(['slug' => 'premium']);
    Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'amount' => 100,
    ]);

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Conteúdo Premium')
        ->set('form.description', 'Descrição premium')
        ->set('form.publish_type', 'now')
        ->set('form.is_premium', true)
        ->call('save')
        ->assertRedirect(route('contents.my'));

    $this->assertDatabaseHas('contents', [
        'user_id' => $user->id,
        'title' => 'Conteúdo Premium',
        'is_premium' => true,
    ]);
});

it('prevents non-premium user from creating premium content', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Conteúdo Gratuito')
        ->set('form.description', 'Descrição gratuita')
        ->set('form.publish_type', 'now')
        ->set('form.is_premium', true)
        ->call('save')
        ->assertRedirect(route('contents.my'));

    $this->assertDatabaseHas('contents', [
        'user_id' => $user->id,
        'title' => 'Conteúdo Gratuito',
        'is_premium' => false,
    ]);
});

it('validates title minimum length', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'AB')
        ->set('form.description', 'Descrição válida')
        ->set('form.publish_type', 'now')
        ->call('save')
        ->assertHasErrors(['form.title']);
});

it('validates description minimum length', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Título Válido')
        ->set('form.description', 'ABCD')
        ->set('form.publish_type', 'now')
        ->call('save')
        ->assertHasErrors(['form.description']);
});

it('validates required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', '')
        ->set('form.description', '')
        ->call('save')
        ->assertHasErrors(['form.title', 'form.description']);
});

it('validates scheduled date is required when schedule type', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Título')
        ->set('form.description', 'Descrição válida')
        ->set('form.publish_type', 'schedule')
        ->set('form.scheduled_date', null)
        ->call('save')
        ->assertHasErrors(['form.scheduled_date']);
});

it('validates scheduled date must be in the future', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Título')
        ->set('form.description', 'Descrição válida')
        ->set('form.publish_type', 'schedule')
        ->set('form.scheduled_date', now()->subDay()->format('Y-m-d H:i:s'))
        ->call('save')
        ->assertHasErrors(['form.scheduled_date']);
});

it('validates maximum number of files', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $files = [];
    for ($i = 0; $i < 11; $i++) {
        $files[] = UploadedFile::fake()->image("file{$i}.jpg");
    }

    Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Título')
        ->set('form.description', 'Descrição válida')
        ->set('form.publish_type', 'now')
        ->set('form.files', $files)
        ->call('save')
        ->assertHasErrors(['form.files']);
});

it('can remove selected files before saving', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $file1 = UploadedFile::fake()->image('file1.jpg');
    $file2 = UploadedFile::fake()->image('file2.jpg');

    $component = Livewire::actingAs($user)
        ->test(Create::class)
        ->set('form.title', 'Título')
        ->set('form.description', 'Descrição')
        ->set('form.publish_type', 'now')
        ->set('form.files', [$file1, $file2]);

    expect($component->get('form.files'))->toHaveCount(2);

    $component->call('removeFile', 0);

    expect($component->get('form.files'))->toHaveCount(1);
});

it('checks if component shows premium option for premium users', function () {
    $user = User::factory()->create();
    $plan = Plan::factory()->create(['slug' => 'premium']);
    Subscription::factory()->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'amount' => 100,
    ]);

    Livewire::actingAs($user)
        ->test(Create::class)
        ->assertSet('hasActiveSubscription', true);
});

it('checks if component hides premium option for non-premium users', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Create::class)
        ->assertSet('hasActiveSubscription', false);
});
