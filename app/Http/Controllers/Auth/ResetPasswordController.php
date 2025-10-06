<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\Otp;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password form
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (!$token) {
            return redirect()->route('password.request')
                           ->withErrors(['error' => 'Invalid password reset token.']);
        }

        try {
            // Decode token to get email and user type
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);
            
            if (count($parts) !== 2) {
                return redirect()->route('password.request')
                               ->withErrors(['error' => 'Invalid password reset token.']);
            }

            $email = $parts[0];
            $userType = $parts[1];

            return view('auth.reset-password', compact('token', 'email', 'userType'));
            
        } catch (\Exception $e) {
            return redirect()->route('password.request')
                           ->withErrors(['error' => 'Invalid password reset token.']);
        }
    }

    /**
     * Reset the password
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'user_type' => 'required|in:user,hospital,staff,admin',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Verify token
            $decoded = base64_decode($request->token);
            $parts = explode('|', $decoded);
            
            if (count($parts) !== 2 || $parts[0] !== $request->email || $parts[1] !== $request->user_type) {
                return back()->withErrors(['error' => 'Invalid password reset token.'])->withInput();
            }

            // Verify OTP
            $otpRecord = Otp::where('identifier', $request->email)
                            ->where('otp', $request->otp)
                            ->where('type', 'password_reset')
                            ->where('is_used', false)
                            ->where('expires_at', '>', Carbon::now())
                            ->first();

            if (!$otpRecord) {
                return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
            }

            // Find user based on type
            $user = null;
            switch ($request->user_type) {
                case 'user':
                    $user = User::where('email', $request->email)->first();
                    break;
                case 'hospital':
                    $user = Hospital::where('email', $request->email)->first();
                    break;
                case 'staff':
                    $user = Staff::where('email', $request->email)->first();
                    break;
                case 'admin':
                    $user = Admin::where('email', $request->email)->first();
                    break;
            }

            if (!$user) {
                return back()->withErrors(['email' => 'User not found.'])->withInput();
            }

            // Mark OTP as used
            $otpRecord->update(['is_used' => true]);

            // Update password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('login')
                           ->with('success', 'Password reset successfully! You can now login with your new password.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Password reset failed. Please try again.'])->withInput();
        }
    }
}