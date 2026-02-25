<x-slot name="title">{{ $form->content->title }}</x-slot>

<div class="max-w-4xl mx-auto p-6" x-data="{}">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('contents.my') }}" class="text-[#64748b] dark:text-[#94a3b8] hover:text-[#475569] dark:hover:text-[#cbd5e1] font-medium transition-colors flex items-center gap-1">
                <i class="ph ph-arrow-left"></i>
                Ver Meus Conteúdos
            </a>
            @if($canEdit)
                <button
                    wire:click="toggleEdit"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors font-medium flex items-center gap-2"
                >
                    @if($isEditing)
                        <i class="ph ph-x"></i>
                        Cancelar
                    @else
                        <i class="ph ph-pencil-simple"></i>
                        Editar
                    @endif
                </button>
            @endif
        </div>
    </div>

    @if(!$isEditing)
        {{-- View Mode --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">{{ $form->content->title }}</h1>
                    <div class="flex items-center gap-2">
                        @if($form->content->is_premium)
                            <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300">
                                Premium
                            </span>
                        @endif
                        @php
                            $status = 'draft';
                            if ($form->content->published_at) {
                                if ($form->content->published_at <= now()) {
                                    $status = 'published';
                                } else {
                                    $status = 'scheduled';
                                }
                            }
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs
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
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="flex items-center gap-2 text-[#64748b] dark:text-[#94a3b8]">
                    <i class="ph ph-calendar"></i>
                    <span>
                        @if($form->content->published_at)
                            @if($form->content->published_at <= now())
                                Publicado em {{ $form->content->published_at->format('d/m/Y H:i') }}
                            @else
                                Agendado para {{ $form->content->published_at->format('d/m/Y H:i') }}
                            @endif
                        @else
                            Rascunho
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-end gap-6">
                    <div class="flex items-center gap-1 text-[#64748b] dark:text-[#94a3b8]">
                        <i class="ph ph-eye"></i>
                        <span class="font-medium">{{ number_format($form->content->total_views ?? 0) }} views</span>
                    </div>
                    <div class="flex items-center gap-1 text-[#64748b] dark:text-[#94a3b8]">
                        <i class="ph ph-download-simple"></i>
                        <span class="font-medium">{{ number_format($form->content->total_downloads ?? 0) }} downloads</span>
                    </div>
                </div>
            </div>

            <div class="prose dark:prose-invert max-w-none">
                <h3 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Descrição</h3>
                <p class="text-[#475569] dark:text-[#cbd5e1] whitespace-pre-wrap">{{ $form->content->description }}</p>
            </div>
        </div>
    @else
        {{-- Edit Mode --}}
        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6 mb-6">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Editar Conteúdo</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Título</label>
                    <input type="text" wire:model="form.title" class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary">
                    @error('form.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Descrição</label>
                    <textarea wire:model="form.description" rows="4" class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary resize-none"></textarea>
                    @error('form.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-2">Tipo de Publicação</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $form->publish_type === 'now' ? 'border-primary bg-primary/5' : 'border-[#e2e8f0] dark:border-[#334155]' }}">
                            <input type="radio" wire:model.live="form.publish_type" value="now" class="sr-only">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $form->publish_type === 'now' ? 'border-primary bg-primary' : 'border-[#cbd5e1]' }}">
                                @if($form->publish_type === 'now') <div class="w-2 h-2 rounded-full bg-white"></div> @endif
                            </div>
                            <span class="font-medium text-[#1e293b] dark:text-[#f1f5f9]">Publicar agora</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $form->publish_type === 'schedule' ? 'border-primary bg-primary/5' : 'border-[#e2e8f0] dark:border-[#334155]' }}">
                            <input type="radio" wire:model.live="form.publish_type" value="schedule" class="sr-only">
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $form->publish_type === 'schedule' ? 'border-primary bg-primary' : 'border-[#cbd5e1]' }}">
                                @if($form->publish_type === 'schedule') <div class="w-2 h-2 rounded-full bg-white"></div> @endif
                            </div>
                            <span class="font-medium text-[#1e293b] dark:text-[#f1f5f9]">Agendar publicação</span>
                        </label>
                    </div>
                </div>

                @if($form->publish_type === 'schedule')
                    <div>
                        <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">Data de Publicação</label>
                        <input type="datetime-local" wire:model="form.scheduled_date" class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary">
                        @error('form.scheduled_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if(Auth::user()->hasActiveSubscription())
                    <div class="flex items-center gap-3 p-3 rounded-lg border border-[#e2e8f0] dark:border-[#334155]">
                        <input type="checkbox" wire:model="form.is_premium" id="is_premium" class="w-4 h-4 text-primary rounded border-[#e2e8f0] dark:border-[#334155] focus:ring-primary">
                        <label for="is_premium" class="font-medium text-[#1e293b] dark:text-[#f1f5f9]">Conteúdo Premium</label>
                    </div>
                @endif

                <div class="border-t border-[#e2e8f0] dark:border-[#334155] pt-4">
                    <h3 class="text-sm font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-3">Arquivos</h3>
                    
                    @if($form->content->files->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($form->content->files as $file)
                                <div class="flex items-center justify-between p-3 bg-[#f8fafc] dark:bg-[#0f172a] rounded-lg border border-[#e2e8f0] dark:border-[#334155]">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-file text-primary text-xl"></i>
                                        <div>
                                            <p class="text-sm font-medium text-[#1e293b] dark:text-[#f1f5f9]">{{ $file->original_name }}</p>
                                            <p class="text-xs text-[#64748b] dark:text-[#94a3b8]">{{ number_format($file->size / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <button wire:click="removeFile({{ $file->id }})" wire:confirm="Tem certeza que deseja remover este arquivo?" class="text-red-500 hover:text-red-600 p-1 transition-colors">
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-[#64748b] dark:text-[#94a3b8] mb-4">Nenhum arquivo anexado.</p>
                    @endif

                    <h4 class="text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-2">Adicionar Novos Arquivos</h4>
                    <input type="file" wire:model="form.newFiles" multiple class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
                    
                    @if(!empty($newFilePreviews))
                        <div class="mt-3 space-y-2">
                            @foreach($newFilePreviews as $index => $preview)
                                <div class="flex items-center justify-between p-3 bg-[#f8fafc] dark:bg-[#0f172a] rounded-lg border border-[#e2e8f0] dark:border-[#334155]">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-file text-primary text-xl"></i>
                                        <div>
                                            <p class="text-sm font-medium text-[#1e293b] dark:text-[#f1f5f9]">{{ $preview['name'] }}</p>
                                            <p class="text-xs text-[#64748b] dark:text-[#94a3b8]">{{ $preview['size'] }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="removeNewFile({{ $index }})" class="text-red-500 hover:text-red-600 p-1 transition-colors">
                                        <i class="ph ph-x text-lg"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($form->newFiles))
                        <p class="text-sm text-[#64748b] dark:text-[#94a3b8] mt-2">Os novos arquivos serão salvos quando você clicar em "Salvar Alterações".</p>
                    @endif
                </div>

                <div class="flex gap-3 pt-4">
                    <button wire:click="update" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors font-medium">
                        Salvar Alterações
                    </button>
                    <button wire:click="toggleEdit" class="px-6 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] text-[#475569] dark:text-[#cbd5e1] hover:bg-[#f8fafc] dark:hover:bg-[#1e293b] transition-colors font-medium">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Files Section - View Only --}}
    @if(!$isEditing && $form->content->files->count() > 0)
        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6 mb-6">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Arquivos</h2>
            <div class="space-y-3">
                @foreach($form->content->files as $file)
                    <div class="flex items-center justify-between p-4 bg-[#f8fafc] dark:bg-[#0f172a] rounded-lg border border-[#e2e8f0] dark:border-[#334155]">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-file text-primary text-2xl"></i>
                            <div>
                                <p class="font-medium text-[#1e293b] dark:text-[#f1f5f9]">{{ $file->original_name }}</p>
                                <p class="text-sm text-[#64748b] dark:text-[#94a3b8]">{{ number_format($file->size / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        <button wire:click="downloadFile({{ $file->id }})" class="text-primary hover:text-primary/80 p-2 rounded-lg hover:bg-primary/10 transition-colors" title="Download">
                            <i class="ph ph-download-simple text-xl"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Danger Zone --}}
    @if($canEdit)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <i class="ph ph-warning text-red-600 dark:text-red-400 text-2xl mt-0.5"></i>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-1">Zona de Perigo</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-4">A exclusão do conteúdo é irreversível. Todos os arquivos associados também serão removidos permanentemente.</p>
                    <button wire:click="delete" wire:confirm="Tem certeza que deseja excluir este conteúdo? Esta ação não pode ser desfeita." class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="ph ph-trash mr-2"></i>
                        Excluir Conteúdo
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- JavaScript for file download --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('file-download', (data) => {
                const link = document.createElement('a');
                link.href = data[0].url;
                link.download = data[0].name;
                link.click();
            });
        });
    </script>
</div>
