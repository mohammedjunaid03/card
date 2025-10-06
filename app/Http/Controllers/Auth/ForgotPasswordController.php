<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
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
     * Show forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset link email
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

        try {
            // Generate OTP for password reset
            $otp = $this->otpService->generate($request->email, 'password_reset');

            // Store email in session for password reset
            session(['reset_email' => $request->email]);

            // Generate a token for the reset URL
            $token = bin2hex(random_bytes(32));
            session(['reset_token' => $token]);

            return redirect()->route('password.reset', ['token' => $token])
                ->with('success', 'OTP has been sent to your email. Please check your inbox.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['email' => 'Failed to send reset link. Please try again.']);
        }
    }
}
