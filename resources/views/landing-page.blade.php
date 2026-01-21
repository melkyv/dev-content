<div
    x-data="{
        mobileMenuOpen: false,
        faqOpen: null,
        toggleFaq(index) {
            this.faqOpen = this.faqOpen === index ? null : index;
        },
        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }"
    x-init="
        $el.querySelectorAll('.scroll-reveal').forEach(el => {
            new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }).observe(el);
        });
    "
    class="bg-[#f8fafc] dark:bg-[#0f172a] min-h-screen"
>
    {{-- 1. Navbar --}}
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <i class="ph ph-rocket-launch text-2xl text-primary"></i>
                    <span class="text-xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">DevContent</span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Recursos</a>
                    <a href="#how-it-works" class="text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Como Funciona</a>
                    <a href="#pricing" class="text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Preços</a>
                    <a href="#faq" class="text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">FAQ</a>
                </div>

                <div class="hidden md:flex items-center gap-4">
                    <livewire:theme-toggle />
                    <a href="/login" class="text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition font-medium">Entrar</a>
                    <a href="/register" class="btn-primary">Começar Agora</a>
                </div>

                <div class="md:hidden flex items-center gap-4" >
                    <livewire:theme-toggle />
                    <button
                        x-on:click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition"
                        aria-label="Abrir menu"
                    >
                        <i x-show="!mobileMenuOpen" class="ph ph-list text-2xl"></i>
                        <i x-show="mobileMenuOpen" class="ph ph-x text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition duration-200 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-2 opacity-0"
            class="md:hidden bg-white dark:bg-[#0f172a] border-t border-gray-200 dark:border-gray-800"
        >
            <div class="px-4 py-4 space-y-4">
                <a href="#features" class="block text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Recursos</a>
                <a href="#how-it-works" class="block text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Como Funciona</a>
                <a href="#pricing" class="block text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">Preços</a>
                <a href="#faq" class="block text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition">FAQ</a>
                <hr class="border-gray-200 dark:border-gray-800">
                <a href="/login" class="block text-[#1e293b] dark:text-[#f1f5f9] hover:text-primary transition font-medium">Entrar</a>
                <a href="/register" class="btn-primary inline-block w-full text-center">Começar Agora</a>
            </div>
        </div>
    </nav>

    {{-- 2. Hero Section --}}
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center scroll-reveal">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-6">
                    Transforme seu conteúdo em um
                    <span class="text-primary">negócio recorrente</span>
                </h1>
                <p class="text-xl text-[#64748b] dark:text-[#94a3b8] max-w-3xl mx-auto mb-8">
                    Crie conteúdo exclusivo, gerencie assinaturas e processe arquivos automaticamente. Tudo em uma plataforma simples e poderosa.
                </p>
                <div class="flex justify-center">
                    <a href="/register" class="btn-primary text-lg px-8 py-4">Começar Gratuitamente</a>
                </div>
            </div>

            <div class="mt-16 scroll-reveal">
                <div class="relative bg-gradient-to-br from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 rounded-2xl p-8 lg:p-12">
                    <div class="aspect-video bg-white dark:bg-[#1e293b] rounded-lg shadow-2xl flex items-center justify-center">
                        <div class="text-center">
                            <i class="ph ph-rocket-launch text-6xl text-primary mb-4 animate-bounce"></i>
                            <p class="text-[#1e293b] dark:text-[#f1f5f9] font-medium">Sua plataforma está aqui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Features Section --}}
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-[#1e293b]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center scroll-reveal mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                    Recursos poderosos para criadores
                </h2>
                <p class="text-xl text-[#64748b] dark:text-[#94a3b8]">
                    Tudo o que você precisa para gerenciar seu conteúdo e monetizar sua audiência
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $index => $feature)
                    <div class="scroll-reveal card-hover p-6 bg-[#f8fafc] dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-gray-800">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                            <i class="ph ph-{{ $feature['icon'] }} text-2xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-[#64748b] dark:text-[#94a3b8]">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. How It Works Section --}}
    <section id="how-it-works" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center scroll-reveal mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                    Como funciona
                </h2>
                <p class="text-xl text-[#64748b] dark:text-[#94a3b8]">
                    Comece em menos de 5 minutos
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @foreach($howItWorks as $index => $step)
                    <div class="scroll-reveal relative">
                        <div class="text-6xl font-bold text-primary/20 mb-4">{{ $step['step'] }}</div>
                        <div class="bg-white dark:bg-[#1e293b] p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-lg">
                            <h3 class="text-xl font-semibold text-[#1e293b] dark:text-[#f1f5f9] mb-2">{{ $step['title'] }}</h3>
                            <p class="text-[#64748b] dark:text-[#94a3b8]">{{ $step['description'] }}</p>
                        </div>
                        @if($index < count($howItWorks) - 1)
                            <div class="hidden lg:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                                <i class="ph ph-arrow-right text-2xl text-primary"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. Pricing Section --}}
    <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-[#1e293b]">
        <div class="max-w-7xl mx-auto">
                <div class="text-center scroll-reveal mb-8">
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                        Planos flexíveis para todos
                    </h2>
                    <p class="text-xl text-[#64748b] dark:text-[#94a3b8] mb-8">
                        Escolha o plano ideal para seu tipo de conteúdo
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Free Plan --}}
                    <div class="scroll-reveal card-hover p-8 bg-[#f8fafc] dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-gray-800 flex flex-col">
                        <h3 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Free</h3>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">R$ 0</span>
                            <span class="text-[#64748b] dark:text-[#94a3b8]">/mês</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                3 posts por mês
                            </li>
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                100MB de armazenamento
                            </li>
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                Suporte básico
                            </li>
                        </ul>
                        <a href="/register" class="btn-secondary w-full text-center block mt-auto">Começar Grátis</a>
                    </div>

                    {{-- Pro Plan --}}
                    <div class="scroll-reveal card-hover p-8 bg-gradient-to-br from-primary to-secondary rounded-xl border-2 border-primary relative transform lg:scale-105 flex flex-col">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-accent text-white text-sm font-semibold px-3 py-1 rounded-full">Mais Popular</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-4xl font-bold text-white">R$ 49</span>
                            <span class="text-white/80">/mês</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-center gap-2 text-white">
                                <i class="ph ph-check text-white"></i>
                                Conteúdo ilimitado
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <i class="ph ph-check text-white"></i>
                                10GB de armazenamento
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <i class="ph ph-check text-white"></i>
                                Suporte prioritário
                            </li>
                            <li class="flex items-center gap-2 text-white">
                                <i class="ph ph-check text-white"></i>
                                Analytics avançado
                            </li>
                        </ul>
                        <a href="/register" class="btn-primary w-full text-center block bg-white text-primary hover:bg-gray-100 border-0 mt-auto">Assinar Pro</a>
                    </div>

                    {{-- Enterprise Plan --}}
                    <div class="scroll-reveal card-hover p-8 bg-[#f8fafc] dark:bg-[#0f172a] rounded-xl border border-gray-200 dark:border-gray-800 flex flex-col">
                        <h3 class="text-2xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-2">Enterprise</h3>
                        <div class="flex items-baseline gap-1 mb-6">
                            <span class="text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9]">R$ 199</span>
                            <span class="text-[#64748b] dark:text-[#94a3b8]">/mês</span>
                        </div>
                        <ul class="space-y-3 mb-8 flex-grow">
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                Tudo do Pro
                            </li>
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph ph-check text-green-500"></i>
                                API completa
                            </li>
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                SSO corporativo
                            </li>
                            <li class="flex items-center gap-2 text-[#1e293b] dark:text-[#f1f5f9]">
                                <i class="ph ph-check text-green-500"></i>
                                Suporte dedicado
                            </li>
                        </ul>
                        <a href="/register" class="btn-secondary w-full text-center block mt-auto">Falar com Vendas</a>
                    </div>
            </div>
        </div>
    </section>

    {{-- 6. FAQ Section --}}
    <section id="faq" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center scroll-reveal mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1e293b] dark:text-[#f1f5f9] mb-4">
                    Perguntas frequentes
                </h2>
                <p class="text-xl text-[#64748b] dark:text-[#94a3b8]">
                    Encontre respostas para as dúvidas mais comuns
                </p>
            </div>

            <div class="space-y-4">
                @foreach($faqs as $index => $faq)
                    <div class="scroll-reveal bg-white dark:bg-[#1e293b] rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <button
                            x-on:click="toggleFaq({{ $index }})"
                            class="w-full px-6 py-4 flex justify-between items-center text-left"
                        >
                            <span class="font-semibold text-[#1e293b] dark:text-[#f1f5f9]">{{ $faq['question'] }}</span>
                            <i
                                x-show="faqOpen !== {{ $index }}"
                                class="ph ph-caret-down text-xl text-primary"
                            ></i>
                            <i
                                x-show="faqOpen === {{ $index }}"
                                class="ph ph-caret-up text-xl text-primary"
                            ></i>
                        </button>
                        <div
                            x-show="faqOpen === {{ $index }}"
                            x-transition:enter="transition duration-200 ease-out"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition duration-200 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="px-6 pb-4"
                        >
                            <p class="text-[#64748b] dark:text-[#94a3b8]">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 7. CTA Final --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-primary to-secondary">
        <div class="max-w-4xl mx-auto text-center scroll-reveal">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                Pronto para começar sua jornada?
            </h2>
            <p class="text-xl text-white/80 mb-8">
                Junte-se a milhares de criadores que estão transformando seu conteúdo em negócios recorrentes
            </p>
            <button
                x-on:click="scrollToTop()"
                class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 text-lg"
            >
                Começar Agora - É Grátis
            </button>
        </div>
    </section>

    {{-- 8. Footer --}}
    <footer class="bg-[#1e293b] dark:bg-[#0f172a] text-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ph ph-rocket-launch text-2xl text-primary"></i>
                        <span class="text-xl font-bold">DevContent</span>
                    </div>
                    <p class="text-gray-400">
                        Transforme seu conteúdo em um negócio recorrente com nossa plataforma completa.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Redes Sociais</h4>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition" aria-label="LinkedIn">
                            <i class="ph ph-linkedin-logo text-2xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition" aria-label="GitHub">
                            <i class="ph ph-github-logo text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                <p>&copy; 2026 DevContent. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</div>