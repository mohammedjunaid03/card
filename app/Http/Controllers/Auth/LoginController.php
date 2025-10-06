<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ?
                        ['email' => $request->identifier, 'password' => $request->password] :
                        ['mobile' => $request->identifier, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['identifier' => 'Account not active. Please verify your email.'])
                    ->withInput($request->except('password'));
            }

            $request->session()->regenerate();

            // Redirect based on user type
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff.dashboard');
            } elseif ($user->role === 'hospital') {
                return redirect()->route('hospital.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return redirect()->back()
            ->withErrors(['identifier' => 'Invalid credentials.'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
