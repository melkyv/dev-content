<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Navbar extends Component
{
    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
