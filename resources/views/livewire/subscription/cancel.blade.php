<x-slot name="title">Pagamento Cancelado</x-slot>

<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-warning text-3xl text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Pagamento Cancelado</h1>
            <p class="text-[#64748b] dark:text-[#94a3b8]">
                O processo de pagamento foi cancelado. Nenhuma cobrança foi realizada.
            </p>
        </div>

        <div class="space-y-4">
            <p class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                Você pode tentar novamente a qualquer momento. Caso tenha alguma dúvida, entre em contato com nosso suporte.
            </p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ route('subscription') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    <i class="ph ph-crown-simple mr-2"></i>
                    Tentar Novamente
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#334155] text-[#1e293b] dark:text-[#f1f5f9] border border-[#e2e8f0] dark:border-[#475569] rounded-lg hover:bg-[#f8fafc] dark:hover:bg-[#475569] transition-colors">
                    <i class="ph ph-house mr-2"></i>
                    Ir para Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
