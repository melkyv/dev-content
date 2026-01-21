<?php

namespace App\Livewire;

use Livewire\Component;

class LandingPage extends Component
{
    public array $features = [
        [
            'icon' => 'user-plus',
            'title' => 'Login Social',
            'description' => 'Google, GitHub e Facebook - um clique para acessar',
        ],
        [
            'icon' => 'credit-card',
            'title' => 'Pagamentos',
            'description' => 'Integração nativa com Stripe para assinaturas',
        ],
        [
            'icon' => 'upload-simple',
            'title' => 'Upload',
            'description' => 'Arraste e solte qualquer tipo de arquivo',
        ],
        [
            'icon' => 'lightning',
            'title' => 'Processamento',
            'description' => 'Jobs assíncronos para máxima performance',
        ],
        [
            'icon' => 'chart-line-up',
            'title' => 'Analytics',
            'description' => 'Métricas em tempo real do seu conteúdo',
        ],
        [
            'icon' => 'lock-key',
            'title' => 'Segurança',
            'description' => 'Criptografia end-to-end para proteção',
        ],
    ];

    public array $howItWorks = [
        [
            'step' => '01',
            'title' => 'Crie sua conta',
            'description' => 'Registre-se com login social ou e-mail em segundos',
        ],
        [
            'step' => '02',
            'title' => 'Escolha seu plano',
            'description' => 'Selecione o plano ideal para seu tipo de conteúdo',
        ],
        [
            'step' => '03',
            'title' => 'Publique seu conteúdo',
            'description' => 'Faça upload e deixe o processamento automático cuidar do resto',
        ],
    ];

    public array $faqs = [
        [
            'question' => 'O plano Free é realmente gratuito?',
            'answer' => 'Sim! O plano Free é gratuito para sempre, sem cartão de crédito.',
        ],
        [
            'question' => 'Posso mudar de plano a qualquer momento?',
            'answer' => 'Sim, você pode fazer upgrade ou downgrade a qualquer momento.',
        ],
        [
            'question' => 'Como funciona o processamento assíncrono?',
            'answer' => 'Usamos Laravel Jobs para processar arquivos em background sem bloquear a interface.',
        ],
        [
            'question' => 'Aceitam quais formas de pagamento?',
            'answer' => 'Aceitamos todas as formas de pagamento suportadas pelo Stripe.',
        ],
    ];

    public function render()
    {
        return view('landing-page');
    }
}
