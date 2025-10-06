<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }

        if (Auth::guard('hospital')->check()) {
            return redirect()->route('hospital.dashboard');
        }

        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'password' => $request->password,
            'remember' => $request->remember ?? false,
        ];

        // Determine credentials based on login type
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->login;
        } else {
            $credentials['mobile'] = $request->login;
        }

        $guard = $request->user_type;
        $redirectRoute = $guard . '.dashboard';

        if (Auth::guard($guard)->attempt($credentials, $request->remember ?? false)) {
            $user = Auth::guard($guard)->user();

            // Check if user is active (for user guard)
            if ($guard === 'web' && $user->status !== 'active') {
                Auth::guard($guard)->logout();
                return redirect()->back()
                    ->withErrors(['login' => 'Your account is not active. Please contact support.'])
                    ->withInput();
            }

            // Check if hospital/staff/admin is approved (you might want to add status checks)
            if (in_array($guard, ['hospital', 'staff', 'admin']) && isset($user->status) && $user->status !== 'active') {
                Auth::guard($guard)->logout();
                return redirect()->back()
                    ->withErrors(['login' => 'Your account is not approved yet. Please contact administrator.'])
                    ->withInput();
            }

            return redirect()->route($redirectRoute);
        }

        return redirect()->back()
            ->withErrors(['login' => 'Invalid credentials or insufficient permissions.'])
            ->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Logout from all guards
        Auth::guard('web')->logout();
        Auth::guard('hospital')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('admin')->logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}