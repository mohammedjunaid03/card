<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Hospital;
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
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string|max:500',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|regex:/^[0-9]{10}$/|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
            'aadhaar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Upload files
            $aadhaarPath = null;
            $photoPath = null;

            if ($request->hasFile('aadhaar')) {
                $aadhaarPath = $request->file('aadhaar')->store('private/aadhaar', 'local');
            }

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/photos');
            }

            // Calculate age
            $dob = Carbon::parse($request->date_of_birth);
            $age = $dob->age;

            // Create user
            $user = User::create([
                'name' => $request->name,
                'dob' => $request->date_of_birth,
                'age' => $age,
                'gender' => $request->gender,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'aadhaar_path' => $aadhaarPath,
                'photo_path' => $photoPath,
                'status' => 'pending',
                'email_verified' => false,
            ]);

            // Generate and send OTP
            $otp = $this->otpService->generateOtp($user->email, 'registration');
            
            // TODO: Send OTP via email
            // For now, we'll just store it and show a message

            return redirect()->route('otp.verify')
                           ->with('success', 'Registration successful! Please verify your email with the OTP sent.')
                           ->with('email', $user->email);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->get('email');
        
        if (!$email) {
            return redirect()->route('register')->withErrors(['error' => 'Invalid access to OTP verification.']);
        }

        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $otpRecord = Otp::where('identifier', $request->email)
                        ->where('otp', $request->otp)
                        ->where('type', 'registration')
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
        }

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Activate user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'status' => 'active',
                'email_verified' => true,
                'email_verified_at' => Carbon::now(),
            ]);

            // TODO: Generate health card PDF and send email
            
            return redirect()->route('login')
                           ->with('success', 'Email verified successfully! Your account is now active. You can login now.');
        }

        return back()->withErrors(['error' => 'User not found.'])->withInput();
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid email.']);
        }

        try {
            // Check if user is already verified
            $user = User::where('email', $request->email)->first();
            if ($user && $user->email_verified) {
                return response()->json(['success' => false, 'message' => 'Email already verified.']);
            }

            // Generate new OTP
            $otp = $this->otpService->generateOtp($request->email, 'registration');
            
            // TODO: Send OTP via email
            
            return response()->json(['success' => true, 'message' => 'OTP sent successfully.']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP.']);
        }
    }
}