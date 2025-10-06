<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use App\Models\Admin;
use App\Models\Otp;
use App\Services\OtpService;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link (OTP)
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'user_type' => 'required|in:user,hospital,staff,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;
        $userType = $request->user_type;
        $user = null;

        // Check if email exists in the specified user type
        switch ($userType) {
            case 'user':
                $user = User::where('email', $email)->first();
                break;
            case 'hospital':
                $user = Hospital::where('email', $email)->first();
                break;
            case 'staff':
                $user = Staff::where('email', $email)->first();
                break;
            case 'admin':
                $user = Admin::where('email', $email)->first();
                break;
        }

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.'])->withInput();
        }

        try {
            // Generate OTP for password reset
            $otp = $this->otpService->generateOtp($email, 'password_reset');
            
            // TODO: Send OTP via email
            
            return redirect()->route('password.reset', ['token' => base64_encode($email . '|' . $userType)])
                           ->with('success', 'Password reset OTP has been sent to your email.');
                           
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send password reset OTP. Please try again.'])->withInput();
        }
    }
}