<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

test('can see forgot password page', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('can request password reset with valid email', function () {
    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors();
});

test('cannot request reset with invalid email', function () {
    Livewire::test(ForgotPassword::class)
        ->set('email', 'nonexistent@example.com')
        ->call('sendResetLink')
        ->assertHasErrors(['email']);
});

test('receives success message after requesting reset', function () {
    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendResetLink')
        ->assertSee('Link de redefinição enviado para seu email!');
});

test('can see reset password page with valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

    $response->assertStatus(200);
});

test('can reset password with valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token])
        ->set('email', $user->email)
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'newpassword123')
        ->call('resetPassword')
        ->assertRedirect('/login');
});

test('cannot reset password with invalid token', function () {
    $user = User::factory()->create();

    Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
        ->set('email', $user->email)
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'newpassword123')
        ->call('resetPassword')
        ->assertHasErrors(['email']);
});

test('is redirected to login after successful reset', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token])
        ->set('email', $user->email)
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'newpassword123')
        ->call('resetPassword')
        ->assertRedirect('/login');
});

test('sees validation error for password mismatch', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token])
        ->set('email', $user->email)
        ->set('password', 'newpassword123')
        ->set('password_confirmation', 'different123')
        ->call('resetPassword')
        ->assertHasErrors(['password']);
});

test('sees validation error for short password', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    Livewire::test(ResetPassword::class, ['token' => $token])
        ->set('email', $user->email)
        ->set('password', 'short')
        ->set('password_confirmation', 'short')
        ->call('resetPassword')
        ->assertHasErrors(['password']);
});
