<x-slot name="title">Criar Conteúdo</x-slot>

<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">Criar Novo Conteúdo</h1>
        <p class="text-[#64748b] dark:text-[#94a3b8]">Compartilhe seu conteúdo com a comunidade</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Informações do Conteúdo</h2>

            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        wire:model="form.title"
                        class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                        placeholder="Digite o título do conteúdo"
                    >
                    @error('form.title')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                        Descrição <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="description"
                        wire:model="form.description"
                        rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors resize-none"
                        placeholder="Descreva seu conteúdo"
                    ></textarea>
                    @error('form.description')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Arquivos</h2>

            <div class="space-y-4">
                <div>
                    <label for="files" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-1">
                        Anexar Arquivos
                    </label>
                    <input
                        type="file"
                        id="files"
                        wire:model="form.files"
                        multiple
                        class="w-full px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90"
                    >
                    <p class="text-sm text-[#64748b] dark:text-[#94a3b8] mt-1">
                        Máximo 10 arquivos, 10MB cada
                    </p>
                    @error('form.files')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    @error('form.files.*')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                @if (!empty($filePreviews))
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-[#475569] dark:text-[#cbd5e1]">Arquivos selecionados:</p>
                        <div class="space-y-2">
                            @foreach ($filePreviews as $index => $preview)
                                <div class="flex items-center justify-between p-3 bg-[#f8fafc] dark:bg-[#0f172a] rounded-lg border border-[#e2e8f0] dark:border-[#334155]">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-file text-primary text-xl"></i>
                                        <div>
                                            <p class="text-sm font-medium text-[#1e293b] dark:text-[#f1f5f9]">{{ $preview['name'] }}</p>
                                            <p class="text-xs text-[#64748b] dark:text-[#94a3b8]">{{ $preview['size'] }}</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="removeFile({{ $index }})"
                                        class="text-red-500 hover:text-red-600 p-1 transition-colors"
                                    >
                                        <i class="ph ph-x text-lg"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
            <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Publicação</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-3">
                        Quando publicar?
                    </label>
                    <div class="grid gap-3">
                        <label 
                            class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                {{ $form->publish_type === 'now' 
                                    ? 'border-primary bg-primary/5 dark:bg-primary/10' 
                                    : 'border-[#e2e8f0] dark:border-[#334155] hover:border-primary/50 hover:bg-[#f8fafc] dark:hover:bg-[#1e293b]' }}"
                        >
                            <input
                                type="radio"
                                wire:model.live="form.publish_type"
                                value="now"
                                class="peer sr-only"
                            >
                            <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors
                                {{ $form->publish_type === 'now'
                                    ? 'border-primary bg-primary'
                                    : 'border-[#cbd5e1] dark:border-[#475569]' }}">
                                @if($form->publish_type === 'now')
                                    <div class="w-2.5 h-2.5 rounded-full bg-white"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <span class="block text-sm font-semibold text-[#1e293b] dark:text-[#f1f5f9]">Publicar agora</span>
                                <span class="block text-xs text-[#64748b] dark:text-[#94a3b8] mt-0.5">O conteúdo ficará disponível imediatamente</span>
                            </div>
                            @if($form->publish_type === 'now')
                                <i class="ph ph-check-circle text-primary text-xl"></i>
                            @endif
                        </label>

                        <label 
                            class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                {{ $form->publish_type === 'schedule' 
                                    ? 'border-primary bg-primary/5 dark:bg-primary/10' 
                                    : 'border-[#e2e8f0] dark:border-[#334155] hover:border-primary/50 hover:bg-[#f8fafc] dark:hover:bg-[#1e293b]' }}"
                        >
                            <input
                                type="radio"
                                wire:model.live="form.publish_type"
                                value="schedule"
                                class="peer sr-only"
                            >
                            <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors
                                {{ $form->publish_type === 'schedule'
                                    ? 'border-primary bg-primary'
                                    : 'border-[#cbd5e1] dark:border-[#475569]' }}">
                                @if($form->publish_type === 'schedule')
                                    <div class="w-2.5 h-2.5 rounded-full bg-white"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <span class="block text-sm font-semibold text-[#1e293b] dark:text-[#f1f5f9]">Agendar publicação</span>
                                <span class="block text-xs text-[#64748b] dark:text-[#94a3b8] mt-0.5">Escolha uma data futura para publicar</span>
                            </div>
                            @if($form->publish_type === 'schedule')
                                <i class="ph ph-calendar-check text-primary text-xl"></i>
                            @endif
                        </label>
                    </div>
                    @error('form.publish_type')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                @if ($form->publish_type === 'schedule')
                    <div class="pt-2 border-t border-[#e2e8f0] dark:border-[#334155]">
                        <label for="scheduled_date" class="block text-sm font-medium text-[#475569] dark:text-[#cbd5e1] mb-2">
                            Data de Publicação <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-calendar text-[#94a3b8]"></i>
                            </div>
                            <input
                                type="datetime-local"
                                id="scheduled_date"
                                wire:model="form.scheduled_date"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#e2e8f0] dark:border-[#334155] bg-white dark:bg-[#0f172a] text-[#1e293b] dark:text-[#f1f5f9] focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                            >
                        </div>
                        @error('form.scheduled_date')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
        </div>

        @if ($hasActiveSubscription)
            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
                <h2 class="text-lg font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Tipo de Conteúdo</h2>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-[#e2e8f0] dark:border-[#334155] cursor-pointer hover:bg-[#f8fafc] dark:hover:bg-[#0f172a] transition-colors">
                        <input
                            type="checkbox"
                            wire:model="form.is_premium"
                            class="w-4 h-4 text-primary border-[#e2e8f0] dark:border-[#334155] rounded focus:ring-primary"
                        >
                        <div>
                            <span class="text-sm font-medium text-[#1e293b] dark:text-[#f1f5f9]">Conteúdo Premium</span>
                            <p class="text-xs text-[#64748b] dark:text-[#94a3b8]">Disponível apenas para assinantes premium</p>
                        </div>
                    </label>
                </div>
            </div>
        @else
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="ph ph-info text-amber-600 dark:text-amber-400 text-xl mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Conteúdo Gratuito</p>
                        <p class="text-sm text-amber-700 dark:text-amber-300">
                            Assine o plano premium para criar conteúdos exclusivos para assinantes.
                            <a href="{{ route('subscription') }}" class="underline hover:no-underline">Ver planos</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <a
                href="{{ route('contents.my') }}"
                class="text-[#64748b] dark:text-[#94a3b8] hover:text-[#475569] dark:hover:text-[#cbd5e1] font-medium transition-colors"
            >
                <i class="ph ph-arrow-left mr-1"></i>
                Voltar
            </a>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="save">
                    <i class="ph ph-check-circle"></i>
                    Criar Conteúdo
                </span>
                <span wire:loading wire:target="save">
                    <i class="ph ph-spinner animate-spin"></i>
                    Criando...
                </span>
            </button>
        </div>
    </form>
</div>
