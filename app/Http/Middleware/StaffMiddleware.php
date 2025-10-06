<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('staff')->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (auth('staff')->user()->status !== 'active') {
            auth('staff')->logout();
            return redirect()->route('login')->with('error', 'Your staff account is not active. Please contact support.');
        }

        return $next($request);
    }
}
