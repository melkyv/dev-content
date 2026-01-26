<div class="px-4 sm:px-6 lg:px-8 mt-5 mb-5">
    @if($dashboardState === 'new-user')
        {{-- ESTADO 1: Novo Usuário (sem conteúdos) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Plano Atual --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Plano Atual</h2>
                <div class="flex items-center justify-between">
                    @if($isPremium)
                        <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 font-semibold">
                            Premium
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 font-semibold">
                            Free
                        </span>
                    @endif
                    <a href="{{ route('subscription') }}" class="btn-primary">
                        Assinar Premium
                    </a>
                </div>
            </div>

            {{-- Conteúdos Disponíveis --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Conteúdos Disponíveis</h2>
                <p class="text-4xl font-bold text-primary mb-2">{{ $totalPlatformContents }}</p>
                <p class="text-[#64748b] dark:text-[#94a3b8]">conteúdos publicados na plataforma</p>
            </div>
        </div>

        {{-- Seus Conteúdos --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Seus Conteúdos</h2>
            <p class="text-4xl font-bold text-gray-400 mb-4">0</p>
            <p class="text-[#64748b] dark:text-[#94a3b8]">publicados</p>
        </div>

        {{-- Onboarding --}}
        <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl border border-primary/20 p-8 text-center">
            <i class="ph ph-lightbulb text-6xl text-primary mb-4"></i>
            <h3 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                Comece criando seu primeiro conteúdo ou explore os conteúdos existentes
            </h3>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('contents.create') }}" class="btn-primary">
                    <i class="ph ph-plus mr-1"></i>
                    Criar conteúdo
                </a>
                <a href="{{ route('contents.index') }}" class="btn-secondary">
                    <i class="ph ph-globe mr-1"></i>
                    Explorar conteúdos
                </a>
            </div>
        </div>

    @elseif($dashboardState === 'with-content')
        {{-- ESTADO 2: Usuário com Conteúdos --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Plano Atual --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Plano Atual</h2>
                @if($isPremium)
                    <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 font-semibold">
                        Premium
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 font-semibold">
                        Free
                    </span>
                @endif
            </div>

            {{-- Métricas --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Métricas</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <p class="text-3xl font-bold text-primary">{{ number_format($totalViews) }}</p>
                        <p class="text-[#64748b] dark:text-[#94a3b8] text-sm">Visualizações</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-secondary">{{ number_format($totalDownloads) }}</p>
                        <p class="text-[#64748b] dark:text-[#94a3b8] text-sm">Downloads</p>
                    </div>
                </div>
            </div>

        {{-- Seus Conteúdos Recentes --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9]">Seus Conteúdos</h2>
                <span class="text-[#64748b] dark:text-[#94a3b8]">{{ $myContents->count() }} publicados</span>
            </div>

            @foreach($myContents as $content)
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                    <div class="flex-1">
                        <h3 class="font-semibold text-[#1e293b] dark:text-[#f1f5f9]">{{ $content->title }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            @if($content->is_premium)
                                <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                    Premium
                                </span>
                            @endif
                            <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                                {{ $content->published_at?->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs
                        {{ $content->published_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $content->published_at ? 'Publicado' : 'Rascunho' }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Arquivos em Processamento --}}
        @if($processingFiles > 0)
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 mb-8">
                <div class="flex items-center gap-4">
                    <i class="ph ph-spinner text-3xl text-yellow-600 dark:text-yellow-400 animate-spin"></i>
                    <div>
                        <h3 class="font-semibold text-yellow-800 dark:text-yellow-200">Arquivos em processamento</h3>
                        <p class="text-yellow-700 dark:text-yellow-300">{{ $processingFiles }} arquivo(s) sendo processado(s)</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- CTA Secundário --}}
        <div class="text-center">
            <a href="{{ route('contents.create') }}" class="btn-primary">
                <i class="ph ph-plus mr-1"></i>
                Criar novo conteúdo
            </a>
        </div>

    @elseif($dashboardState === 'premium')
        {{-- ESTADO 3: Usuário Premium --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Plano Premium --}}
            <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl text-white p-6">
                <h2 class="text-lg font-semibold mb-4">Plano Premium</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Status</span>
                        <span class="font-bold">Ativo</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Próxima cobrança</span>
                        <span class="font-bold">
                            {{ $user->subscription?->ends_at?->format('d/m/Y') ?? '--/--/----' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Valor mensal</span>
                        <span class="font-bold">R$ 19,90</span>
                    </div>
                </div>
            </div>

            {{-- Conteúdos Premium Acessíveis --}}
            <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Conteúdos Premium Acessíveis</h2>
                <p class="text-4xl font-bold text-primary mb-2">{{ Content::where('is_premium', true)->whereNotNull('published_at')->count() }}</p>
                <p class="text-[#64748b] dark:text-[#94a3b8]">disponíveis para você</p>
            </div>
        </div>

        {{-- Últimos Conteúdos Premium --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 p-6 mb-8">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-6">Últimos Conteúdos Premium</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($premiumContents as $content)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition duration-300">
                        <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 inline-block mb-2">
                            Premium
                        </span>
                        <h3 class="font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-2 truncate">{{ $content->title }}</h3>
                        <p class="text-[#64748b] dark:text-[#94a3b8] text-sm line-clamp-2 mb-2">{{ \Illuminate\Support\Str::limit($content->description, 80) }}</p>
                        <div class="flex items-center justify-between text-sm text-[#64748b] dark:text-[#94a3b8]">
                            <span>Por {{ $content->user->name }}</span>
                            <span>{{ $content->published_at?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CTA Secundário --}}
        <div class="text-center">
            <a href="{{ route('contents.index') }}" class="btn-secondary">
                <i class="ph ph-globe mr-1"></i>
                Explorar novos conteúdos Premium
            </a>
        </div>
    @endif
</div>
