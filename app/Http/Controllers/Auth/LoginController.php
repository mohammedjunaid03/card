<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string', // email or mobile
            'password' => 'required|string',
        ]);

        // Clear all existing sessions before login
        Auth::guard('web')->logout();
        Auth::guard('hospital')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();

        $guard = match ($request->input('user_type')) {
            'user' => 'web',
            'hospital' => 'hospital',
            'staff' => 'staff',
            'admin' => 'admin',
        };

        $loginInput = $request->input('login');
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $remember = $request->boolean('remember');

        if (!Auth::guard($guard)->attempt([$loginField => $loginInput, 'password' => $request->password], $remember)) {
            throw ValidationException::withMessages([
                'login' => 'Invalid credentials for the selected role.',
            ]);
        }

        $user = Auth::guard($guard)->user();
        $status = $user->status ?? null;
        
        // **CORRECTED LOGIC:** Only block users whose accounts are explicitly inactive or blocked.
        // Allow 'pending' users to pass through to their dashboard controller/view.
        if ($status === 'blocked' || $status === 'inactive') { 
            Auth::guard($guard)->logout();
            throw ValidationException::withMessages([
                'login' => 'Your account is currently disabled. Please contact support.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnGuard($guard);
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        $guards = ['web', 'hospital', 'staff', 'admin'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user based on their guard/role.
     */
    private function redirectBasedOnGuard($guard)
    {
        switch ($guard) {
            case 'web':
                return redirect()->intended(route('user.dashboard'));
            case 'hospital':
                return redirect()->intended(route('hospital.dashboard'));
            case 'staff':
                return redirect()->intended(route('staff.dashboard'));
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            default:
                return redirect()->intended('/');
        }
    }
}
