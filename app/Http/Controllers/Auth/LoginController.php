<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string', // email or mobile
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        $userType = $validated['user_type'];
        $identifier = $validated['login'];
        $password = $validated['password'];
        $remember = (bool) ($validated['remember'] ?? false);

        $guard = match ($userType) {
            'user' => 'web',
            'hospital' => 'hospital',
            'staff' => 'staff',
            'admin' => 'admin',
            default => 'web',
        };

        // Determine credential field
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;
        $credentialField = $isEmail ? 'email' : 'mobile';

        // Admins authenticate by email only
        if ($userType === 'admin' && !$isEmail) {
            return back()
                ->withErrors(['login' => 'Admins must sign in using a valid email address.'])
                ->withInput($request->only(['user_type', 'login']));
        }

        // Only some guards have remember_token column; restrict remember accordingly
        $rememberAllowedGuards = ['web', 'staff'];
        if (! in_array($guard, $rememberAllowedGuards, true)) {
            $remember = false;
        }

        $credentials = [
            $credentialField => $identifier,
            'password' => $password,
        ];

        if (! Auth::guard($guard)->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['login' => 'Invalid credentials or inactive account.'])
                ->withInput($request->only(['user_type', 'login']));
        }

        $request->session()->regenerate();

        // Post-auth status checks per guard
        $authenticatedUser = Auth::guard($guard)->user();
        $isDisallowed = match ($userType) {
            'user' => (($authenticatedUser->status ?? null) !== 'active'),
            'hospital' => (($authenticatedUser->status ?? null) !== 'approved'),
            'staff' => (($authenticatedUser->status ?? null) !== 'active'),
            'admin' => false,
            default => false,
        };

        if ($isDisallowed) {
            Auth::guard($guard)->logout();
            return back()
                ->withErrors(['login' => 'Your account is not permitted to sign in.'])
                ->withInput($request->only(['user_type', 'login']));
        }

        $redirectRoute = match ($userType) {
            'user' => 'user.dashboard',
            'hospital' => 'hospital.dashboard',
            'staff' => 'staff.dashboard',
            'admin' => 'admin.dashboard',
            default => 'home',
        };

        return redirect()->route($redirectRoute);
    }

    public function logout(Request $request)
    {
        foreach (['web', 'hospital', 'staff', 'admin'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
