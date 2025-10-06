<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::guard('web')->user();
        if ($user->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
        
        return $next($request);
    }
}
