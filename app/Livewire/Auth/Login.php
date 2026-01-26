<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $throttleKey = 'login:'.strtolower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('email', 'Muitas tentativas. Tente novamente em '.RateLimiter::availableIn($throttleKey).' segundos.');

            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();

            $this->redirect(route('dashboard'), navigate: true);
        }

        RateLimiter::hit($throttleKey);

        $this->addError('email', 'As credenciais fornecidas estão incorretas.');
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
