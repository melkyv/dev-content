<div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 p-8">
    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-2 mb-4">
            <i class="ph ph-rocket-launch text-3xl text-primary"></i>
            <span class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">DevContent</span>
        </div>
        <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Crie sua conta!</h1>
        <p class="text-[#64748b] dark:text-[#94a3b8]">Comece gratuitamente em segundos</p>
    </div>

    {{-- Form --}}
    <form wire:submit="register">
        {{-- Name --}}
        <div class="mb-4">
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Nome</label>
            <input
                type="text"
                wire:model="name"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-gray-400"
                placeholder="Seu nome"
                autofocus
            >
            @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

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
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Senha</label>
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
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Confirmar Senha</label>
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
            <span wire:loading>Criando conta...</span>
            <span wire:loading.remove>Criar Conta</span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="flex items-center gap-4 my-6">
        <div class="flex-1 border-t border-gray-200 dark:border-gray-700"></div>
        <span class="text-[#64748b] dark:text-[#94a3b8] text-sm">Ou registre-se com</span>
        <div class="flex-1 border-t border-gray-200 dark:border-gray-700"></div>
    </div>

    {{-- OAuth Buttons (Placeholder) --}}
    <div class="space-y-3">
        <button
            type="button"
            class="btn-secondary w-full flex items-center justify-center gap-2"
            disabled
        >
            <i class="ph ph-google-logo text-xl"></i>
            Continuar com Google
        </button>
        <button
            type="button"
            class="btn-secondary w-full flex items-center justify-center gap-2"
            disabled
        >
            <i class="ph ph-github-logo text-xl"></i>
            Continuar com GitHub
        </button>
    </div>

    {{-- Login Link --}}
    <p class="text-center mt-6 text-[#64748b] dark:text-[#94a3b8]">
        Já tem uma conta?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">
            Entrar
        </a>
    </p>
</div>
