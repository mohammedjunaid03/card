<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /**
     * Show the login form. If already authenticated on any guard,
     * redirect to the appropriate dashboard.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }

        if (Auth::guard('hospital')->check()) {
            return redirect()->route('hospital.dashboard');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string', // email or mobile
            'password' => 'required|string',
            'remember' => 'nullable',
        ]);

        $identifier = $validated['login'];
        $password = $validated['password'];
        $remember = (bool) $request->boolean('remember');

        $guard = $this->mapUserTypeToGuard($validated['user_type']);

        // Build credentials based on identifier format
        $credentialsList = $this->buildCredentialPermutations($validated['user_type'], $identifier, $password);

        $authenticated = false;
        foreach ($credentialsList as $credentials) {
            if (Auth::guard($guard)->attempt($credentials, $remember)) {
                $authenticated = true;
                break;
            }
        }

        if (! $authenticated) {
            return back()
                ->withErrors(['login' => 'Invalid credentials.'])
                ->withInput($request->only('user_type', 'login', 'remember'));
        }

        // Regenerate the session to prevent fixation
        $request->session()->regenerate();

        // Enforce active status across actors
        $user = Auth::guard($guard)->user();
        if (method_exists($user, 'isActive') && ! $user->isActive()) {
            Auth::guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['login' => 'Account is not active.'])
                ->withInput($request->only('user_type', 'login'));
        }

        return redirect()->intended($this->dashboardRouteFor($validated['user_type']));
    }

    /**
     * Log the user out of the application (supports all guards).
     */
    public function logout(Request $request)
    {
        foreach (['admin', 'staff', 'hospital', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function mapUserTypeToGuard(string $userType): string
    {
        return match ($userType) {
            'admin' => 'admin',
            'staff' => 'staff',
            'hospital' => 'hospital',
            default => 'web',
        };
    }

    /**
     * Return guard redirect route for dashboards per user type.
     */
    private function dashboardRouteFor(string $userType): string
    {
        return match ($userType) {
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'hospital' => 'hospital.dashboard',
            default => 'user.dashboard',
        };
    }

    /**
     * Build one or more credential payloads to attempt for the given user type.
     * For users/hospitals/staff we allow email OR mobile identifiers.
     * For admins we only allow email.
     */
    private function buildCredentialPermutations(string $userType, string $identifier, string $password): array
    {
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;

        // Admins: email only
        if ($userType === 'admin') {
            return [
                ['email' => $identifier, 'password' => $password],
            ];
        }

        // Others: support email or mobile
        if ($isEmail) {
            return [
                ['email' => $identifier, 'password' => $password],
            ];
        }

        return [
            ['mobile' => $identifier, 'password' => $password],
            // Fallback attempt as email just in case input looked like phone but is email
            ['email' => $identifier, 'password' => $password],
        ];
    }
}
