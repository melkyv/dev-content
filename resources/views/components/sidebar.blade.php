<aside class="hidden md:block fixed left-0 top-16 bottom-0 w-64 bg-white dark:bg-[#1e293b] border-r border-gray-200 dark:border-gray-800 overflow-y-auto z-40">
    <div class="p-4 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-house text-xl"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        {{-- Conteúdos --}}
        <a href="{{ route('contents.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('contents.index') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-files text-xl"></i>
            <span class="font-medium">Conteúdos</span>
        </a>

        {{-- Novo Conteúdo --}}
        <a href="{{ route('contents.create') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('contents.create') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-plus-circle text-xl"></i>
            <span class="font-medium">Novo Conteúdo</span>
        </a>

        {{-- Meus Conteúdos --}}
        <a href="{{ route('contents.my') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('contents.my') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-folder text-xl"></i>
            <span class="font-medium">Meus Conteúdos</span>
        </a>

        {{-- Assinatura --}}
        <a href="{{ route('subscription') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('subscription') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-credit-card text-xl"></i>
            <span class="font-medium">Assinatura</span>
        </a>

        {{-- Perfil --}}
        <a href="{{ route('profile') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                  {{ request()->routeIs('profile') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
            <i class="ph ph-user text-xl"></i>
            <span class="font-medium">Perfil</span>
        </a>
    </div>
</aside>

{{-- Mobile Sidebar --}}
<div x-data="{ sidebarOpen: false }" class="md:hidden">
    {{-- Sidebar Off-canvas --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition duration-300 ease-out"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-in"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-0 z-50 flex">
        
        {{-- Sidebar Content --}}
        <div class="w-64 bg-white dark:bg-[#1e293b] h-full overflow-y-auto">
            <div class="p-4 space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-house text-xl"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                {{-- Conteúdos --}}
                <a href="{{ route('contents.index') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('contents.*') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-files text-xl"></i>
                    <span class="font-medium">Conteúdos</span>
                </a>

                {{-- Novo Conteúdo --}}
                <a href="{{ route('contents.create') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('contents.create') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-plus-circle text-xl"></i>
                    <span class="font-medium">Novo Conteúdo</span>
                </a>

                {{-- Meus Conteúdos --}}
                <a href="{{ route('contents.my') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('contents.my') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-folder text-xl"></i>
                    <span class="font-medium">Meus Conteúdos</span>
                </a>

                {{-- Assinatura --}}
                <a href="{{ route('subscription') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('subscription') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-credit-card text-xl"></i>
                    <span class="font-medium">Assinatura</span>
                </a>

                {{-- Perfil --}}
                <a href="{{ route('profile') }}"
                   @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
                          {{ request()->routeIs('profile') ? 'bg-primary/10 text-primary' : 'text-[#64748b] dark:text-[#94a3b8] hover:bg-gray-100 dark:hover:bg-[#0f172a]' }}">
                    <i class="ph ph-user text-xl"></i>
                    <span class="font-medium">Perfil</span>
                </a>
            </div>
        </div>

        {{-- Overlay escuro --}}
        <div @click="sidebarOpen = false" class="flex-1 bg-black/50 z-40"></div>
    </div>

    {{-- Botão FAB para abrir sidebar (mobile) --}}
    <button @click="sidebarOpen = true" class="fixed bottom-4 right-4 z-[60] p-4 bg-primary text-white rounded-full shadow-lg hover:bg-primary-dark transition duration-300">
        <i class="ph ph-list text-2xl"></i>
    </button>
</div>
