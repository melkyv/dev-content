<nav class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-[#1e293b] border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Lado Esquerdo --}}
            <div class="flex items-center gap-6">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <i class="ph ph-rocket-launch text-2xl text-primary"></i>
                    <span class="text-xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">DevContent</span>
                </a>
            </div>

            {{-- Lado Direito --}}
                {{-- Toogle theme --}}
                <div class="flex items-center gap-4">
                    <div class="absolute top-4 right-4 z-50">
                    <livewire:theme-toggle />
                </div>

                {{-- Badge do Plano --}}
                <span class="hidden sm:block px-3 py-1 rounded-full text-sm font-semibold {{ auth()->user()->hasActiveSubscription() ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ auth()->user()->subscription?->plan?->name ?? 'Free' }}
                </span>

                {{-- Avatar + Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none cursor-pointer">
                        @if(auth()->user()->avatar_path)
                            <img src="{{ auth()->user()->avatarUrl }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold text-lg border-2 border-gray-200 dark:border-gray-700">
                                {{ auth()->user()->initials }}
                            </div>
                        @endif
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#1e293b] rounded-lg shadow-xl border border-gray-200 dark:border-gray-800 py-1 z-50">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9]">
                            <i class="ph ph-user text-lg"></i>
                            Meu perfil
                        </a>
                        <a href="{{ route('subscription') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9]">
                            <i class="ph ph-credit-card text-lg"></i>
                            Minha assinatura
                        </a>
                        <a href="{{ route('settings') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9]">
                            <i class="ph ph-gear text-lg"></i>
                            Configurações
                        </a>
                        <hr class="my-1 border-gray-200 dark:border-gray-700">
                        <button wire:click="logout" class="w-full flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#0f172a] text-left text-[#1e293b] dark:text-[#f1f5f9]">
                            <i class="ph ph-sign-out text-lg"></i>
                            Sair
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
