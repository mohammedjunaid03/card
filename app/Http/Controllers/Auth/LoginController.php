<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use App\Models\Admin;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string', // email or mobile (from form)
            'password' => 'required|string',
            'user_type' => 'required|in:user,hospital,staff,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $identifier = $request->login;
        $password = $request->password;
        $userType = $request->user_type;

        // Determine if identifier is email or mobile
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        
        $credentials = [
            $field => $identifier,
            'password' => $password
        ];

        // Attempt authentication based on user type
        $authenticated = false;
        $user = null;
        $guard = 'web'; // default guard

        switch ($userType) {
            case 'user':
                if (Auth::guard('web')->attempt($credentials)) {
                    $user = Auth::guard('web')->user();
                    if ($user->status === 'active') {
                        $authenticated = true;
                        $guard = 'web';
                    } else {
                        Auth::guard('web')->logout();
                        return back()->withErrors(['login' => 'Your account is not active.'])->withInput();
                    }
                }
                break;

            case 'hospital':
                if (Auth::guard('hospital')->attempt($credentials)) {
                    $user = Auth::guard('hospital')->user();
                    if ($user->status === 'approved') {
                        $authenticated = true;
                        $guard = 'hospital';
                    } else {
                        Auth::guard('hospital')->logout();
                        return back()->withErrors(['login' => 'Your hospital account is not approved yet.'])->withInput();
                    }
                }
                break;

            case 'staff':
                if (Auth::guard('staff')->attempt($credentials)) {
                    $user = Auth::guard('staff')->user();
                    if ($user->status === 'active') {
                        $authenticated = true;
                        $guard = 'staff';
                    } else {
                        Auth::guard('staff')->logout();
                        return back()->withErrors(['login' => 'Your staff account is not active.'])->withInput();
                    }
                }
                break;

            case 'admin':
                if (Auth::guard('admin')->attempt($credentials)) {
                    $user = Auth::guard('admin')->user();
                    if ($user->status === 'active') {
                        $authenticated = true;
                        $guard = 'admin';
                    } else {
                        Auth::guard('admin')->logout();
                        return back()->withErrors(['login' => 'Your admin account is not active.'])->withInput();
                    }
                }
                break;
        }

        if ($authenticated) {
            $request->session()->regenerate();
            
            // Redirect to appropriate dashboard based on user type
            switch ($userType) {
                case 'user':
                    return redirect()->intended(route('user.dashboard'));
                case 'hospital':
                    return redirect()->intended(route('hospital.dashboard'));
                case 'staff':
                    return redirect()->intended(route('staff.dashboard'));
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Determine which guard to logout from
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('hospital')->check()) {
            Auth::guard('hospital')->logout();
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}