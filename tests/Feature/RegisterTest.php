<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

test('can see register page', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('can register with valid data', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('cannot register with existing email', function () {
    $user = User::factory()->create();

    Livewire::test(Register::class)
        ->set('name', 'Another User')
        ->set('email', $user->email)
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasErrors(['email']);
});

test('is logged in after registration', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register');

    $this->assertAuthenticated();
});

test('is redirected to dashboard after registration', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertRedirect('/dashboard');
});

test('sees validation errors for empty fields', function () {
    Livewire::test(Register::class)
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

test('sees validation error for password mismatch', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'different123')
        ->call('register')
        ->assertHasErrors(['password']);
});

test('sees validation error for short password', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'short')
        ->set('password_confirmation', 'short')
        ->call('register')
        ->assertHasErrors(['password']);
});
