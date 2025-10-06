<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\Otp;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset link
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user
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

        // Generate OTP for password reset
        $otp = rand(100000, 999999);
        Otp::create([
            'identifier' => $request->email,
            'otp' => $otp,
            'type' => 'password_reset',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email in production

        return redirect()->route('password.reset')
            ->with('success', 'Password reset OTP sent to your email!')
            ->with('email', $request->email);
    }
}