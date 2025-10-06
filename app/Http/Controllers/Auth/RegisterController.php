<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Otp;
use App\Services\OtpService;
use Carbon\Carbon;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string|max:500',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|unique:users,mobile|regex:/^[0-9]{10}$/',
            'password' => 'required|string|min:8|confirmed',
            'aadhaar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            // Upload files
            $aadhaarPath = $request->file('aadhaar')->store('private/aadhaar');
            $photoPath = $request->file('photo') ? $request->file('photo')->store('public/photos') : null;

            // Calculate age if not provided
            $dob = Carbon::parse($request->date_of_birth);
            $age = $request->age ?? $dob->age;

            // Create user
            $user = User::create([
                'name' => $request->name,
                'date_of_birth' => $request->date_of_birth,
                'age' => $age,
                'gender' => $request->gender,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => $request->password,
                'aadhaar_path' => $aadhaarPath,
                'photo_path' => $photoPath,
                'status' => 'pending',
            ]);

            // Generate and send OTP
            $otp = $this->otpService->generate($user->email, 'registration');

            // Store user ID in session for OTP verification
            session(['registration_email' => $user->email]);

            return redirect()->route('otp.verify')
                ->with('success', 'Registration successful! Please verify your email with the OTP sent to ' . $user->email);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Registration failed. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('registration_email')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Invalid session. Please register again.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $email = session('registration_email');
        
        if (!$email) {
            return redirect()->route('register')
                ->withErrors(['error' => 'Invalid session. Please register again.']);
        }

        $otpRecord = Otp::where('identifier', $email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('type', 'registration')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return redirect()->back()
                ->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Activate user
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'status' => 'active',
                'email_verified' => true,
            ]);
        }

        // Clear session
        session()->forget('registration_email');

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now login.');
    }
}
