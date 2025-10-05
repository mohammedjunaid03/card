<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHospitalRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('hospital')->check()) {
            return redirect()->route('login');
        }
        
        if (Auth::guard('hospital')->user()->status !== 'approved') {
            return redirect()->route('hospital.pending')->with('error', 'Your account is pending approval');
        }
        
        return $next($request);
    }
}