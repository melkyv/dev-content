<x-slot name="title">Meus Conteúdos</x-slot>

<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">Meus Conteúdos</h1>
                <p class="text-[#64748b] dark:text-[#94a3b8]">Gerencie seus conteúdos publicados</p>
            </div>
            <a href="{{ route('contents.create') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-primary/90 transition-colors font-medium flex items-center gap-2">
                <i class="ph ph-plus-circle text-xl"></i>
                Novo Conteúdo
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ph ph-magnifying-glass text-[#94a3b8]"></i>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por título..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Status</label>
                <select
                    wire:model.live="status"
                    class="w-full h-10 px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                >
                    <option value="">Todos</option>
                    <option value="published">Publicados</option>
                    <option value="draft">Rascunhos</option>
                    <option value="scheduled">Agendados</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Tipo</label>
                <select
                    wire:model.live="type"
                    class="w-full h-10 px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                >
                    <option value="">Todos</option>
                    <option value="premium">Premium</option>
                    <option value="free">Gratuito</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Ordenar por</label>
                <select
                    wire:model.live="sortBy"
                    class="w-full h-10 px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                >
                    <option value="recent">Mais recente</option>
                    <option value="views">Mais views</option>
                    <option value="downloads">Mais downloads</option>
                </select>
            </div>

            <div class="flex items-end">
                <button
                    wire:click="resetFilters"
                    class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] text-[#475569] dark:text-[#cbd5e1] hover:bg-[#f8fafc] dark:hover:bg-[#1e293b] hover:border-primary/50 dark:hover:border-primary/50 transition-all duration-200 font-medium flex items-center justify-center gap-2"
                >
                    <i class="ph ph-faders"></i>
                    Limpar filtros
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] overflow-hidden">
        @if($contents->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#f8fafc] dark:bg-[#0f172a] border-b border-[#e2e8f0] dark:border-[#334155]">
                            <th class="text-left px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Título</th>
                            <th class="text-left px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Data</th>
                            <th class="text-center px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Views</th>
                            <th class="text-center px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Downloads</th>
                            <th class="text-center px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Arquivos</th>
                            <th class="text-right px-6 py-4 text-sm font-semibold text-[#475569] dark:text-[#cbd5e1]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0] dark:divide-[#334155]">
                        @foreach($contents as $content)
                            <tr class="hover:bg-[#f8fafc] dark:hover:bg-[#0f172a] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-[#1e293b] dark:text-[#f1f5f9]">{{ $content->title }}</span>
                                        @if($content->is_premium)
                                            <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300">
                                                Premium
                                            </span>
                                        @endif
                                    </div>
                                    @php
                                        $status = 'draft';
                                        if ($content->published_at) {
                                            if ($content->published_at <= now()) {
                                                $status = 'published';
                                            } else {
                                                $status = 'scheduled';
                                            }
                                        }
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs mt-1
                                        {{ match($status) {
                                            'published' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                            'draft' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                                            'scheduled' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                        } }}">
                                        @if($status === 'published')
                                            <i class="ph ph-check-circle"></i> Publicado
                                        @elseif($status === 'draft')
                                            <i class="ph ph-pencil-simple"></i> Rascunho
                                        @else
                                            <i class="ph ph-clock"></i> Agendado
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($content->published_at)
                                        @if($content->published_at <= now())
                                            <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                                                {{ $content->published_at->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                                                {{ $content->published_at->format('d/m/Y H:i') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-sm text-[#94a3b8] dark:text-[#64748b]">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 text-[#64748b] dark:text-[#94a3b8]">
                                        <i class="ph ph-eye"></i>
                                        <span class="font-medium">{{ number_format($content->total_views) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1 text-[#64748b] dark:text-[#94a3b8]">
                                        <i class="ph ph-download-simple"></i>
                                        <span class="font-medium">{{ number_format($content->total_downloads) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($content->files->count() > 0)
                                        <div class="flex items-center justify-center gap-1 text-[#64748b] dark:text-[#94a3b8]">
                                            <i class="ph ph-files"></i>
                                            <span class="font-medium">{{ $content->files->count() }}</span>
                                        </div>
                                    @else
                                        <span class="text-[#94a3b8] dark:text-[#64748b]">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('contents.show', $content->id) }}"
                                            class="text-primary hover:text-primary/80 p-2 rounded-lg hover:bg-primary/10 dark:hover:bg-primary/20 transition-colors"
                                            title="Visualizar"
                                        >
                                            <i class="ph ph-eye text-xl"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-[#e2e8f0] dark:border-[#334155]">
                {{ $contents->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#f8fafc] dark:bg-[#0f172a] flex items-center justify-center">
                    <i class="ph ph-folder-open text-3xl text-[#94a3b8]"></i>
                </div>
                <h3 class="text-lg font-medium text-[#1e293b] dark:text-[#f1f5f9] mb-2">Nenhum conteúdo encontrado</h3>
                <p class="text-[#64748b] dark:text-[#94a3b8] mb-4">Você ainda não criou nenhum conteúdo ou nenhum corresponde aos filtros selecionados.</p>
                <a href="{{ route('contents.create') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-medium transition-colors">
                    <i class="ph ph-plus-circle"></i>
                    Criar conteúdo
                </a>
            </div>
        @endif
    </div>
</div>
