<button
    wire:click="toggleTheme"
    wire:loading.attr="disabled"
    class="p-2 text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition disabled:opacity-50"
    aria-label="Alternar tema"
>
    <i class="ph ph-moon text-xl {{ $isDarkMode ? 'hidden' : '' }}"></i>
    <i class="ph ph-sun text-xl {{ $isDarkMode ? '' : 'hidden' }}"></i>
</button>