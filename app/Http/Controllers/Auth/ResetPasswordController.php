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
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset the password
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $otpRecord = Otp::where('identifier', $request->email)
                        ->where('otp', $request->otp)
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid or expired OTP.'])
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        $otpRecord->update(['is_used' => true]);

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password; // hashed via mutator
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password reset successful. You can now login with your new password.');
    }
}
