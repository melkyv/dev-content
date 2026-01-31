<x-slot name="title">Pagamento Concluído</x-slot>

<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white dark:bg-[#1e293b] rounded-xl shadow-sm border border-[#e2e8f0] dark:border-[#334155] p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-check-circle text-3xl text-green-600 dark:text-green-400"></i>
            </div>
            <h1 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Pagamento Concluído!</h1>
            <p class="text-[#64748b] dark:text-[#94a3b8]">
                Obrigado pela sua assinatura! Estamos processando seu pagamento e ativando sua assinatura.
            </p>
        </div>

        <div class="space-y-4">
            <p class="text-sm text-[#64748b] dark:text-[#94a3b8]">
                Você receberá um e-mail de confirmação em breve. O acesso aos recursos premium será ativado assim que o pagamento for confirmado.
            </p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ route('subscription') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                    <i class="ph ph-crown-simple mr-2"></i>
                    Ver Minha Assinatura
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#334155] text-[#1e293b] dark:text-[#f1f5f9] border border-[#e2e8f0] dark:border-[#475569] rounded-lg hover:bg-[#f8fafc] dark:hover:bg-[#475569] transition-colors">
                    <i class="ph ph-house mr-2"></i>
                    Ir para Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
