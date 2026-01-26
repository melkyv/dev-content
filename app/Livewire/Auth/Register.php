<?php

namespace App\Livewire\Auth;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate();

        $throttleKey = 'register:'.strtolower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $this->addError('email', 'Muitas tentativas. Tente novamente em '.RateLimiter::availableIn($throttleKey).' segundos.');

            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $plan = Plan::where('slug', 'free')->first();

        if ($plan) {
            $user->subscription()->create([
                'plan_id' => $plan->id,
                'status' => 'active',
            ]);
        }

        Auth::login($user);

        RateLimiter::clear($throttleKey);

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
