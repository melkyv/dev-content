<div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 p-8">
    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-2 mb-4">
            <i class="ph ph-rocket-launch text-3xl text-primary"></i>
            <span class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">DevContent</span>
        </div>
        <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Redefinir senha</h1>
        <p class="text-[#64748b] dark:text-[#94a3b8]">Crie uma nova senha segura</p>
    </div>

    {{-- Form --}}
    <form wire:submit="resetPassword">
        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Email</label>
            <input
                type="email"
                wire:model="email"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-gray-400"
                placeholder="seu@email.com"
            >
            @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Nova Senha</label>
            <input
                type="password"
                wire:model="password"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-gray-400"
                placeholder="•••••••"
            >
            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Confirmar Nova Senha</label>
            <input
                type="password"
                wire:model="password_confirmation"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-gray-400"
                placeholder="•••••••"
            >
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading>Redefinindo...</span>
            <span wire:loading.remove>Redefinir Senha</span>
        </button>
    </form>

    {{-- Back to Login --}}
    <p class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-[#64748b] dark:text-[#94a3b8] hover:text-primary transition">
            <i class="ph ph-arrow-left mr-1"></i>
            Voltar para Login
        </a>
    </p>
</div>
