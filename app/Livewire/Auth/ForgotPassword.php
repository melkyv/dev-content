<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $status = null;

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = 'Link de redefinição enviado para seu email!';
        } else {
            $this->addError('email', __($status));
        }
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
