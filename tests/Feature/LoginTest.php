<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

test('can see login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password123')
        ->call('login')
        ->assertRedirect('/dashboard');
});

test('cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrongpassword')
        ->call('login')
        ->assertHasErrors()
        ->assertSee('As credenciais fornecidas estão incorretas.');
});

test('sees validation errors for empty fields', function () {
    Livewire::test(Login::class)
        ->call('login')
        ->assertHasErrors(['email', 'password']);
});

test('sees validation error for invalid email format', function () {
    Livewire::test(Login::class)
        ->set('email', 'invalid-email')
        ->set('password', 'password123')
        ->call('login')
        ->assertHasErrors(['email']);
});

test('can access forgot password page', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});
