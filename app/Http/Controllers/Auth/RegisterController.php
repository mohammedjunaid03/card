<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Services\CardGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    protected $cardGeneratorService;

    public function __construct(CardGeneratorService $cardGeneratorService)
    {
        $this->cardGeneratorService = $cardGeneratorService;
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
            'aadhaar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|max:1024',
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

        // Calculate age
        $dob = Carbon::parse($request->date_of_birth);
        $age = $dob->age;

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
            'email_verified' => false,
            'mobile_verified' => false,
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        Otp::create([
            'identifier' => $user->email,
            'otp' => $otp,
            'type' => 'registration',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email/SMS
        // For now, we'll store it in session for testing
        session(['registration_otp' => $otp, 'user_id' => $user->id]);

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful. Please verify your email with the OTP sent to your email address.');
    }

    /**
     * Show OTP verification form.
     */
    public function showOtpForm()
    {
        if (!session('registration_otp')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = Otp::where('identifier', session('user_email'))
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
        $user = User::find(session('user_id'));
        if ($user) {
            $user->update([
                'status' => 'active',
                'email_verified' => true,
            ]);

            // Generate health card
            $healthCard = $this->cardGeneratorService->generateCard($user);

            // Clear session
            session()->forget(['registration_otp', 'user_id', 'user_email']);

            // Login user
            Auth::login($user);

            return redirect()->route('user.dashboard')
                ->with('success', 'Account verified successfully! Your health card has been generated.');
        }

        return redirect()->route('login')
            ->with('error', 'Verification failed. Please try again.');
    }
}
