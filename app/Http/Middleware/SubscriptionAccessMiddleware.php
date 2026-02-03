<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $hasStripeSession = $request->has('session_id') &&
                          str_starts_with($request->get('session_id'), 'cs_');

        $hasRecentCheckout = session()->has('stripe_checkout_recent');

        if (! $hasStripeSession && ! $hasRecentCheckout) {
            return redirect()->route('subscription');
        }

        session()->forget('stripe_checkout_recent');

        return $next($request);
    }
}
