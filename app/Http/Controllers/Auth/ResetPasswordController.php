<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Show reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Verify token matches session
        if ($token !== session('reset_token')) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Invalid or expired reset token.']);
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Verify token
        if ($request->token !== session('reset_token')) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Invalid reset token.']);
        }

        // Verify email matches session
        if ($request->email !== session('reset_email')) {
            return redirect()->back()
                ->withErrors(['email' => 'Email does not match reset request.']);
        }

        // Verify OTP
        $otpRecord = Otp::where('identifier', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('type', 'password_reset')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid or expired OTP.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Update password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update(['password' => $request->password]);
        }

        // Clear session
        session()->forget(['reset_email', 'reset_token']);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully! You can now login with your new password.');
    }
}
