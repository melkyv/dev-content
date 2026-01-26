<div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 p-8">
    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-2 mb-4">
            <i class="ph ph-rocket-launch text-3xl text-primary"></i>
            <span class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">DevContent</span>
        </div>
        <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Esqueceu sua senha?</h1>
        <p class="text-[#64748b] dark:text-[#94a3b8]">Insira seu email para receber o link de reset</p>
    </div>

    {{-- Success Message --}}
    @if ($status)
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-2 text-green-700 dark:text-green-400">
                <i class="ph ph-check-circle text-xl"></i>
                <span>{{ $status }}</span>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form wire:submit="sendResetLink">
        {{-- Email --}}
        <div class="mb-6">
            <label class="block text-[#1e293b] dark:text-[#f1f5f9] font-medium mb-2">Email</label>
            <input
                type="email"
                wire:model="email"
                class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition placeholder-gray-400"
                placeholder="seu@email.com"
                autofocus
            >
            @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading>Enviando...</span>
            <span wire:loading.remove>Enviar Link de Reset</span>
        </button>
    </form>

    {{-- Back to Login --}}
    <p class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-[#64748b] dark:text-[#94a3b8] hover:text-primary transition">
            <i class="ph ph-arrow-left mr-1"></i>
            Lembrei minha senha! Voltar
        </a>
    </p>
</div>
