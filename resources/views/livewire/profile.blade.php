<x-slot name="title">Meu Perfil</x-slot>

<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-6">Meu Perfil</h1>

    <div class="grid md:grid-cols-3 gap-6">
        {{-- Coluna da Esquerda - Avatar --}}
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Foto de Perfil</h2>

                <div class="flex flex-col items-center">
                    <div class="relative mb-4">
                        @if ($avatarPreviewUrl)
                            <img src="{{ $avatarPreviewUrl }}"
                                 alt="Preview do avatar"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-primary/20">
                        @elseif (auth()->user()->avatar_path)
                            <img src="{{ auth()->user()->avatarUrl }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-primary/20">
                        @else
                            <div class="w-32 h-32 rounded-full bg-primary/10 flex items-center justify-center border-4 border-primary/20">
                                <span class="text-4xl font-bold text-primary">{{ auth()->user()->initials }}</span>
                            </div>
                        @endif

                        <label for="avatar-upload"
                               class="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full cursor-pointer hover:bg-primary/90 transition-colors shadow-lg">
                            <i class="ph ph-camera text-xl"></i>
                        </label>
                        <input type="file"
                               id="avatar-upload"
                               wire:model="avatar"
                               class="hidden"
                               accept="image/jpeg,image/png,image/webp">
                    </div>

                    @error('avatar')
                        <span class="text-red-500 text-sm mb-2">{{ $message }}</span>
                    @enderror

                    <p class="text-sm text-[#64748b] dark:text-[#94a3b8] text-center mb-4">
                        JPG, PNG ou WEBP<br>Máximo 1MB
                    </p>

                    @if (auth()->user()->avatar_path && !filter_var(auth()->user()->avatar_path, FILTER_VALIDATE_URL))
                        <button wire:click="removeAvatar"
                                wire:loading.attr="disabled"
                                class="text-red-500 hover:text-red-600 text-sm font-medium transition-colors">
                            <i class="ph ph-trash mr-1"></i>
                            Remover foto
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Coluna da Direita - Formulários --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Informações Pessoais --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Informações Pessoais</h2>

                <form wire:submit="updateProfile" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                            Nome
                        </label>
                        <input type="text"
                               id="name"
                               wire:model="name"
                               class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="Seu nome completo">
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                            Email
                        </label>
                        <input type="email"
                               id="email"
                               wire:model="email"
                               class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="seu@email.com">
                        @error('email')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors font-medium flex items-center gap-2">
                            <span wire:loading.remove wire:target="updateProfile">
                                <i class="ph ph-floppy-disk"></i>
                                Salvar Alterações
                            </span>
                            <span wire:loading wire:target="updateProfile">
                                <i class="ph ph-spinner animate-spin"></i>
                                Salvando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Alterar/Definir Senha --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                    {{ auth()->user()->password ? 'Alterar Senha' : 'Definir Senha' }}
                </h2>

                <form wire:submit="updatePassword" class="space-y-4">
                    @if (auth()->user()->password)
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                                Senha Atual
                            </label>
                            <input type="password"
                                   id="current_password"
                                   wire:model="current_password"
                                   class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                                   placeholder="••••••••">
                            @error('current_password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                            {{ auth()->user()->password ? 'Nova Senha' : 'Senha' }}
                        </label>
                        <input type="password"
                               id="new_password"
                               wire:model="new_password"
                               class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="Mínimo 8 caracteres">
                        @error('new_password')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                            Confirmar {{ auth()->user()->password ? 'Nova Senha' : 'Senha' }}
                        </label>
                        <input type="password"
                               id="new_password_confirmation"
                               wire:model="new_password_confirmation"
                               class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                               placeholder="••••••••">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="bg-[#475569] dark:bg-[#334155] text-white px-6 py-2 rounded-lg hover:bg-[#334155] dark:hover:bg-[#475569] transition-colors font-medium flex items-center gap-2">
                            <span wire:loading.remove wire:target="updatePassword">
                                <i class="ph ph-lock-key"></i>
                                {{ auth()->user()->password ? 'Atualizar Senha' : 'Definir Senha' }}
                            </span>
                            <span wire:loading wire:target="updatePassword">
                                <i class="ph ph-spinner animate-spin"></i>
                                Atualizando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
