<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('hospital')->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (auth('hospital')->user()->status !== 'approved') {
            auth('hospital')->logout();
            return redirect()->route('login')->with('error', 'Your hospital account is not approved yet. Please contact support.');
        }

        return $next($request);
    }
}