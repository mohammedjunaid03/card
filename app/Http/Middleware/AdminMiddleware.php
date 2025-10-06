<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (auth('admin')->user()->status !== 'active') {
            auth('admin')->logout();
            return redirect()->route('login')->with('error', 'Your admin account is not active. Please contact support.');
        }

        return $next($request);
    }
}
