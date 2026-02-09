<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the profile page for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/profile');

    $response->assertOk();
    $response->assertSee('Meu Perfil');
    $response->assertSee('Informações Pessoais');
    $response->assertSee('Alterar Senha');
});

it('redirects guests to login', function () {
    $this->get('/profile')
        ->assertRedirect('/login');
});

it('can update name and email', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('name', 'New Name')
        ->set('email', 'new@example.com')
        ->call('updateProfile')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
});

it('validates name is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('name', '')
        ->call('updateProfile')
        ->assertHasErrors(['name' => 'required']);
});

it('validates name minimum length', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('name', 'ab')
        ->call('updateProfile')
        ->assertHasErrors(['name' => 'min']);
});

it('validates email is required', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('email', '')
        ->call('updateProfile')
        ->assertHasErrors(['email' => 'required']);
});

it('validates email format', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('email', 'invalid-email')
        ->call('updateProfile')
        ->assertHasErrors(['email' => 'email']);
});

it('validates email is unique', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('email', 'existing@example.com')
        ->call('updateProfile')
        ->assertHasErrors(['email' => 'unique']);
});

it('allows keeping the same email', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('email', 'user@example.com')
        ->call('updateProfile')
        ->assertHasNoErrors();
});

it('can upload avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100)->size(500);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('avatar', $file)
        ->call('updateProfile')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->avatar_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar_path);
});

it('validates avatar must be an image', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->create('document.pdf', 100);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('avatar', $file)
        ->call('updateProfile')
        ->assertHasErrors(['avatar' => 'image']);
});

it('validates avatar max size', function () {
    Storage::fake('public');
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('large-avatar.jpg', 2000, 2000)->size(2048);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('avatar', $file)
        ->call('updateProfile');

    // File should not be stored due to validation failure
    Storage::disk('public')->assertMissing('avatars/'.$file->hashName());
});

it('validates avatar mime types', function () {
    $user = User::factory()->create();

    $file = UploadedFile::fake()->image('avatar.gif')->size(100);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('avatar', $file)
        ->call('updateProfile')
        ->assertHasErrors(['avatar' => 'mimes']);
});

it('can remove avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'avatar_path' => 'avatars/old-avatar.jpg',
    ]);

    Storage::disk('public')->put('avatars/old-avatar.jpg', 'content');

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->call('removeAvatar')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->avatar_path)->toBeNull();
    Storage::disk('public')->assertMissing('avatars/old-avatar.jpg');
});

it('can update password with correct current password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('current-password'),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('current_password', 'current-password')
        ->set('new_password', 'new-secure-password')
        ->set('new_password_confirmation', 'new-secure-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    $user->refresh();

    expect(\Illuminate\Support\Facades\Hash::check('new-secure-password', $user->password))->toBeTrue();
});

it('allows social user to set password without current password', function () {
    $user = User::factory()->create([
        'password' => null,
        'provider' => 'google',
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('new_password', 'new-secure-password')
        ->set('new_password_confirmation', 'new-secure-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->password)->not->toBeNull();
    expect(\Illuminate\Support\Facades\Hash::check('new-secure-password', $user->password))->toBeTrue();
});

it('fails to update password with incorrect current password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('correct-password'),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('current_password', 'wrong-password')
        ->set('new_password', 'new-password')
        ->set('new_password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);
});

it('validates new password minimum length', function () {
    $user = User::factory()->create([
        'password' => bcrypt('current-password'),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('current_password', 'current-password')
        ->set('new_password', 'short')
        ->set('new_password_confirmation', 'short')
        ->call('updatePassword')
        ->assertHasErrors(['new_password' => 'min']);
});

it('validates new password confirmation', function () {
    $user = User::factory()->create([
        'password' => bcrypt('current-password'),
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('current_password', 'current-password')
        ->set('new_password', 'new-password')
        ->set('new_password_confirmation', 'different-password')
        ->call('updatePassword')
        ->assertHasErrors(['new_password' => 'confirmed']);
});

it('requires current password to update password', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Profile::class)
        ->set('current_password', '')
        ->set('new_password', 'new-password')
        ->set('new_password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertHasErrors(['current_password' => 'required']);
});
