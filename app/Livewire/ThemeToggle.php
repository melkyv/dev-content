<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ThemeToggle extends Component
{
    public bool $isDarkMode = false;

    #[On('theme-initialized')]
    public function setTheme($isDarkMode): void
    {
        $this->isDarkMode = $isDarkMode;
    }

    public function toggleTheme(): void
    {
        $this->isDarkMode = ! $this->isDarkMode;
        $this->dispatch('theme-changed', isDarkMode: $this->isDarkMode);
    }
}
