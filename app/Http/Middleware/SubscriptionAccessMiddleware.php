<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Para páginas de sucesso/cancelamento, vamos permitir acesso apenas se vier da Stripe
        // ou se houver uma sessão indicando um processo recente

        $hasStripeSession = $request->has('session_id') &&
                          str_starts_with($request->get('session_id'), 'cs_');

        $hasRecentCheckout = session()->has('stripe_checkout_recent');

        if (! $hasStripeSession && ! $hasRecentCheckout) {
            // Redirecionar para a página de acesso restrito
            return redirect()->route('subscription');
        }

        // Limpar a sessão após acessar
        session()->forget('stripe_checkout_recent');

        return $next($request);
    }
}
