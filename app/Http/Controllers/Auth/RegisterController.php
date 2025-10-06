<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'required|integer|min:1|max:150',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'required|string|max:1000',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
            'aadhaar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload files
        $aadhaarPath = null;
        $photoPath = null;

        if ($request->hasFile('aadhaar')) {
            $aadhaarPath = $request->file('aadhaar')->store('private/aadhaar');
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/photos');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'dob' => $request->date_of_birth,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password, // Mutator handles hashing
            'aadhaar_path' => $aadhaarPath,
            'photo_path' => $photoPath,
            'status' => 'pending', // Requires OTP verification
        ]);

        // Generate OTP for email verification
        $otp = rand(100000, 999999);
        Otp::create([
            'identifier' => $user->email,
            'otp' => $otp,
            'type' => 'registration',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email/SMS in production

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful! Please verify your email with the OTP sent.')
            ->with('email', $user->email);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Please register first to verify your account.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Handle OTP verification
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = Otp::where('identifier', $request->email)
                        ->where('otp', $request->otp)
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid or expired OTP.'])
                ->withInput();
        }

        $otpRecord->update(['is_used' => true]);

        // Activate user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'status' => 'active',
                'email_verified' => true,
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Account verified successfully! You can now login.');
    }
}