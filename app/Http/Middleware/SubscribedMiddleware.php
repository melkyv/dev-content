<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasActiveSubscription()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Assinatura ativa necessária'], 403);
            }

            return redirect()->route('subscription')
                ->with('error', 'Você precisa de uma assinatura ativa para acessar este recurso.');
        }

        return $next($request);
    }
}
