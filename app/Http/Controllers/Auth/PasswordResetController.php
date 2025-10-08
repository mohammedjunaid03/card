<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\Otp;
use App\Mail\PasswordResetMail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:user,staff,admin',
            'email' => 'required|email',
        ]);

        $user = $this->getUserByEmail($request->user_type, $request->email);

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }

        // Generate reset token
        $token = Str::random(64);
        
        // Store token in database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send email
        try {
            Mail::to($request->email)->send(new PasswordResetMail($token, $request->user_type));
            
            return back()->with('status', 'Password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Password reset email failed: ' . $e->getMessage());
            
            // For development, show the reset link directly
            if (config('app.debug')) {
                $resetUrl = route('password.reset', [
                    'token' => $token,
                    'user_type' => $request->user_type,
                    'email' => $request->email
                ]);
                
                return back()->with('status', 'Email sending failed in development mode. Use this link to reset your password: ' . $resetUrl);
            }
            
            return back()->withErrors(['email' => 'Failed to send reset email. Please try again or contact support.']);
        }
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:user,staff,admin',
            'mobile' => 'required|string|min:10|max:10',
        ]);

        $user = $this->getUserByMobile($request->user_type, $request->mobile);

        if (!$user) {
            return back()->withErrors(['mobile' => 'No account found with this mobile number.']);
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in database
        Otp::updateOrCreate(
            [
                'mobile' => $request->mobile,
                'user_type' => $request->user_type,
                'type' => 'password_reset'
            ],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0
            ]
        );

        // In a real application, you would send SMS here
        // For now, we'll just store it and show it in the response
        session(['otp_for_demo' => $otp]);
        
        return redirect()->route('password.verify-otp')
            ->with('mobile', $request->mobile)
            ->with('user_type', $request->user_type)
            ->with('status', 'OTP sent to your mobile number. For demo purposes, OTP is: ' . $otp);
    }

    public function showVerifyOtpForm(Request $request)
    {
        $mobile = $request->session()->get('mobile') ?? $request->mobile;
        $userType = $request->session()->get('user_type') ?? $request->user_type;

        if (!$mobile || !$userType) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', compact('mobile', 'userType'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'user_type' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = Otp::where('mobile', $request->mobile)
            ->where('user_type', $request->user_type)
            ->where('type', 'password_reset')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        if ($otpRecord->attempts >= 3) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Please request a new OTP.']);
        }

        if (!Hash::check($request->otp, $otpRecord->otp)) {
            $otpRecord->increment('attempts');
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP is valid, generate reset token
        $token = Str::random(64);
        
        // Store token in database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->mobile], // Using mobile as identifier for OTP reset
            [
                'email' => $request->mobile,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Delete OTP record
        $otpRecord->delete();

        return redirect()->route('password.reset', [
            'token' => $token,
            'user_type' => $request->user_type,
            'mobile' => $request->mobile
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'user_type' => 'required|string',
        ]);

        return $this->sendOtp($request);
    }

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $userType = $request->user_type ?? 'user';
        $email = $request->email ?? $request->mobile;

        return view('auth.reset-password', compact('token', 'userType', 'email'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'user_type' => 'required|in:user,staff,admin',
            'email' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify token
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        // Check if token is not expired (24 hours)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            return back()->withErrors(['token' => 'Reset token has expired.']);
        }

        // Find user
        $user = $this->getUserByIdentifier($request->user_type, $request->email);

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset successfully. You can now login with your new password.');
    }

    private function getUserByEmail($userType, $email)
    {
        switch ($userType) {
            case 'user':
                return User::where('email', $email)->first();
            case 'staff':
                return Staff::where('email', $email)->first();
            case 'admin':
                return Admin::where('email', $email)->first();
            default:
                return null;
        }
    }

    private function getUserByMobile($userType, $mobile)
    {
        switch ($userType) {
            case 'user':
                return User::where('mobile', $mobile)->first();
            case 'staff':
                return Staff::where('mobile', $mobile)->first();
            case 'admin':
                return Admin::where('mobile', $mobile)->first();
            default:
                return null;
        }
    }

    private function getUserByIdentifier($userType, $identifier)
    {
        // Check if identifier is email or mobile
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return $this->getUserByEmail($userType, $identifier);
        } else {
            return $this->getUserByMobile($userType, $identifier);
        }
    }
}
