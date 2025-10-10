<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show the generic login form.
     */
    public function showLoginForm()
    {
        request()->session()->regenerateToken();
        return view('auth.login'); // generic login form
    }

    /**
     * Handle generic login for multiple guards.
     */
    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $request->validate([
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string', // email or mobile
            'password' => 'required|string',
        ]);

        $this->logoutAllGuards();

        $guard = match ($request->input('user_type')) {
            'hospital' => 'hospital',
            'staff'    => 'staff',
            'admin'    => 'admin',
            default    => 'web',
        };

        $loginInput = $request->input('login');
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $remember   = $request->boolean('remember');
        $nameOrDob  = $request->input('name_or_dob'); // Additional identifier

        // For user login, we need to handle multiple users with same email/phone
        if ($guard === 'web' && $nameOrDob) {
            $users = \App\Models\User::where($loginField, $loginInput)->get();
            
            if ($users->count() > 1) {
                // Multiple users found, need additional identification
                $user = $users->first(function ($user) use ($nameOrDob) {
                    return $user->name === $nameOrDob || $user->date_of_birth === $nameOrDob;
                });
                
                if (!$user || !Hash::check($request->password, $user->password)) {
                    RateLimiter::hit($this->throttleKey($request));
                    throw ValidationException::withMessages(['login' => 'Invalid username or password.']);
                }
                
                // Manually login the user
                Auth::guard($guard)->login($user, $remember);
            } else {
                // Single user or no additional identifier provided
                if (! Auth::guard($guard)->attempt([$loginField => $loginInput, 'password' => $request->password], $remember)) {
                    RateLimiter::hit($this->throttleKey($request));
                    throw ValidationException::withMessages(['login' => 'Invalid username or password.']);
                }
            }
        } else {
            // For other guards (hospital, staff, admin) or user without additional identifier
            if (! Auth::guard($guard)->attempt([$loginField => $loginInput, 'password' => $request->password], $remember)) {
                RateLimiter::hit($this->throttleKey($request));
                throw ValidationException::withMessages(['login' => 'Invalid username or password.']);
            }
        }

        RateLimiter::clear($this->throttleKey($request));
        $user = Auth::guard($guard)->user();

        if (in_array($user->status ?? null, ['inactive', 'blocked', 'pending'])) {
            Auth::guard($guard)->logout();
            throw ValidationException::withMessages(['login' => 'Your account is not active. Please contact support.']);
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnGuard($guard);
    }

    /**
     * Show hospital login form.
     */
    public function showHospitalLoginForm()
    {
        request()->session()->regenerateToken();
        return view('auth.hospital-login'); // create this Blade for hospital login
    }

    /**
     * Handle hospital login only.
     */
    public function hospitalLogin(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('login');
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::guard('hospital')->attempt([$loginField => $loginInput, 'password' => $request->password], $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages(['login' => 'Invalid username or password.']);
        }

        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        return redirect()->intended(route('hospital.dashboard'));
    }

    /**
     * Generic logout method for all guards.
     */
    public function logout(Request $request)
    {
        $this->logoutAllGuards();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    /**
     * Logout hospital user.
     */
    public function hospitalLogout(Request $request)
    {
        Auth::guard('hospital')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hospital.login')->with('success', 'Logged out successfully.');
    }

    /**
     * Logout all guards to prevent conflicts.
     */
    private function logoutAllGuards(): void
    {
        foreach (['web', 'hospital', 'staff', 'admin'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }
    }

    /**
     * Rate-limit login attempts.
     */
    private function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) return;

        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        throw ValidationException::withMessages(['login' => "Too many login attempts. Try again in {$seconds} seconds."]);
    }

    /**
     * Unique throttle key per login attempt.
     */
    private function throttleKey(Request $request): string
    {
        return Str::lower($request->input('login')).'|'.$request->ip();
    }

    /**
     * Redirect user based on guard after login.
     */
    private function redirectBasedOnGuard(string $guard)
    {
        return match ($guard) {
            'web' => redirect()->intended(route('user.dashboard')),
            'hospital' => redirect()->intended(route('hospital.dashboard')),
            'staff' => redirect()->intended(route('staff.dashboard')),
            'admin' => redirect()->intended(route('admin.dashboard')),
            default => redirect('/'),
        };
    }
}
