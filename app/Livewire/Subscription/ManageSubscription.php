<?php

namespace App\Livewire\Subscription;

use App\Models\Plan;
use Livewire\Component;

class ManageSubscription extends Component
{
    public $hasSubscription;

    public $activeSubscription;

    public $plans;

    public $message;

    public function mount()
    {
        $user = auth()->user();
        $this->hasSubscription = $user->hasActiveSubscription();
        $this->activeSubscription = $user->activeSubscription();
        $this->plans = Plan::where('is_active', true)->where('price', '>', 0)->get();
    }

    public function render()
    {
        return view('livewire.subscription.manage-subscription');
    }

    public function redirectToCheckout(Plan $plan)
    {
        $user = auth()->user();

        if (! $plan->stripe_price_id) {
            session()->flash('error', 'Este plano não está disponível para assinatura.');

            return;
        }

        if ($user->subscription?->active()) {
            session()->flash('error', 'Você já possui uma assinatura ativa.');

            return;
        }

        try {
            $stripeService = app(\App\Services\StripeService::class);
            $checkoutUrl = $stripeService->createCheckoutSession($user, $plan);

            // Marcar que iniciou um processo de checkout
            session()->put('stripe_checkout_recent', true);

            return $this->redirect($checkoutUrl);
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível iniciar o processo de assinatura. Tente novamente.');
        }
    }

    public function redirectToBillingPortal()
    {
        $user = auth()->user();

        if (! $user->stripe_customer_id) {
            session()->flash('error', 'Você ainda não possui uma assinatura.');

            return;
        }

        try {
            $stripeService = app(\App\Services\StripeService::class);
            $portalUrl = $stripeService->createBillingPortalSession($user);

            return $this->redirect($portalUrl);
        } catch (\Exception $e) {
            session()->flash('error', 'Não foi possível acessar o portal de faturamento. Tente novamente.');
        }
    }
}
