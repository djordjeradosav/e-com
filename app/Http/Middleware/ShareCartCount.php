<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            Inertia::share([
                'cartCount' => $request->user()->cartItems()->sum('quantity'),
            ]);
        }

        return $next($request);
    }
}