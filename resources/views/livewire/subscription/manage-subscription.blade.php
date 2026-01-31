<x-slot name="title">Assinatura</x-slot>

<div class="max-w-7xl mx-auto p-6">
    @if ($message)
        <div class="mb-6 p-4 rounded-lg {{ $success ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-yellow-50 text-yellow-800 border border-yellow-200' }}">
            {{ $message }}
        </div>
    @endif

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">Gerenciar Assinatura</h1>
        <p class="text-[#64748b] dark:text-[#94a3b8]">Escolha o plano ideal para você</p>
    </div>

    @if (!$hasSubscription)
        <div class="grid md:grid-cols-1 lg:grid-cols-1 gap-6 justify-center max-w-md mx-auto">
            @foreach ($plans as $plan)
                <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6 hover:shadow-lg transition-shadow">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">{{ $plan->name }}</h3>
                        <div class="text-3xl font-bold text-primary mb-4">
                            R$ {{ number_format($plan->price / 100, 2, ',', '.') }}
                            <span class="text-sm font-normal text-[#64748b] dark:text-[#94a3b8]">/mês</span>
                        </div>
                        
                        <div class="text-left space-y-2 mb-6">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-check-circle text-green-500"></i>
                                <span class="text-[#475569] dark:text-[#cbd5e1]">Acesso total a todos os recursos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ph ph-check-circle text-green-500"></i>
                                <span class="text-[#475569] dark:text-[#cbd5e1]">Suporte prioritário</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="ph ph-check-circle text-green-500"></i>
                                <span class="text-[#475569] dark:text-[#cbd5e1]">Atualizações exclusivas</span>
                            </div>
                        </div>

                        <button 
                            wire:click="redirectToCheckout({{ $plan->id }})"
                            class="w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors font-medium"
                        >
                            Assinar Agora
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">
                            Plano {{ $activeSubscription->plan->name }}
                        </h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                @if ($activeSubscription->active())
                                    Ativo
                                @elseif ($activeSubscription->onGracePeriod())
                                    Em período de carência
                                @else
                                    Cancelado
                                @endif
                            </span>
                            @if ($activeSubscription->ends_at)
                                <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                                    @if ($activeSubscription->active())
                                        Renova em {{ $activeSubscription->ends_at->format('d/m/Y') }}
                                    @elseif ($activeSubscription->onGracePeriod())
                                        Cancelado, expira em {{ $activeSubscription->ends_at->format('d/m/Y') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-2xl font-bold text-primary">
                            R$ {{ number_format($activeSubscription->plan->price / 100, 2, ',', '.') }}
                        </div>
                        <div class="text-sm text-[#64748b] dark:text-[#94a3b8]">/mês</div>
                    </div>
                </div>

                <div class="border-t border-[#e2e8f0] dark:border-[#334155] pt-4">
                    <div class="flex gap-4">
                        <button 
                            wire:click="redirectToBillingPortal"
                            class="flex-1 bg-white dark:bg-[#334155] text-[#1e293b] dark:text-[#f1f5f9] px-4 py-2 rounded-lg border border-[#e2e8f0] dark:border-[#475569] hover:bg-[#f8fafc] dark:hover:bg-[#475569] transition-colors font-medium"
                        >
                            Gerenciar Faturamento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
