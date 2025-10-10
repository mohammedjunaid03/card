<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access without authentication - hospital users can login without being authenticated
        // If a hospital user is authenticated, check their status
        if (auth('hospital')->check()) {
            $hospital = auth('hospital')->user();

            if ($hospital->status !== 'active') {
                auth('hospital')->logout();
                return redirect()->route('login', ['role' => 'hospital'])
                    ->with('error', 'Your hospital account is not approved yet. Please contact support.');
            }

            // Refresh session
            $request->session()->regenerate();
        }

        return $next($request);
    }
}