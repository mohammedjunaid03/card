<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return redirect()->back()
                ->withErrors(['email' => 'Email not found.'])
                ->withInput();
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        Otp::create([
            'identifier' => $request->email,
            'otp' => $otp,
            'type' => 'password_reset',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email

        return redirect()->back()
            ->with('success', 'OTP sent to your email for password reset.');
    }
}
