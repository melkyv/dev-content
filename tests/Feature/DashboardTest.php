<?php

use App\Livewire\Dashboard;
use App\Models\User;
use Livewire\Livewire;

test('can access dashboard when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertStatus(200);
});

test('cannot access dashboard when not authenticated', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});

test('is redirected to login when not authenticated', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('can logout', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->call('logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});

test('dashboard loads successfully for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertStatus(200);
});

test('sidebar and navbar are present on dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSee('Dashboard')
        ->assertSee('Conteúdos')
        ->assertSee('Perfil');
});
