<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHospitalRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::guard('web')->user();
        if ($user->role !== 'hospital') {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
        
        return $next($request);
    }
}
