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
        return view('auth.login');
    }

    /**
     * Handle login for multiple user types
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|in:user,hospital,staff,admin',
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Determine login field (email or mobile)
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        
        // Select the appropriate guard based on user type
        $guard = $request->user_type === 'user' ? 'web' : $request->user_type;
        
        // Attempt authentication
        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        if (Auth::guard($guard)->attempt($credentials, $remember)) {
            $user = Auth::guard($guard)->user();

            // Check if user is active
            if (isset($user->status) && $user->status !== 'active') {
                Auth::guard($guard)->logout();
                return redirect()->back()
                    ->withErrors(['login' => 'Your account is not active. Please contact support.'])
                    ->withInput($request->except('password'));
            }

            $request->session()->regenerate();

            // Redirect based on user type
            return $this->redirectToDashboard($request->user_type);
        }

        return redirect()->back()
            ->withErrors(['login' => 'Invalid credentials. Please try again.'])
            ->withInput($request->except('password'));
    }

    /**
     * Redirect to appropriate dashboard based on user type
     */
    protected function redirectToDashboard($userType)
    {
        switch ($userType) {
            case 'user':
                return redirect()->route('user.dashboard');
            case 'hospital':
                return redirect()->route('hospital.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return redirect()->route('home');
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Determine which guard to logout from
        $guards = ['web', 'hospital', 'staff', 'admin'];
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
                break;
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}
