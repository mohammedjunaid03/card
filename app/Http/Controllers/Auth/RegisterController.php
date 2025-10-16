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
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'aadhaar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|max:1024',
        ]);

        // Custom validation: Check if same person already exists
        $existingUser = User::where('email', $request->email)
            ->where('mobile', $request->mobile)
            ->where('name', $request->name)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if ($existingUser) {
            $validator->errors()->add('name', 'A user with the same name, email, phone number, and date of birth already exists.');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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

        // Activate user directly (no payment required)
        $user->update([
            'status' => 'active',
            'email_verified' => true,
            'mobile_verified' => true,
        ]);

        // Generate health card
        $healthCard = $this->cardGeneratorService->generateCard($user);

        // Notify staff about pending approval
        $this->notifyStaffForApproval($healthCard);

        // Login user
        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Registration successful! Your health card has been generated and is pending staff approval.');
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

        $userId = session('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Session expired. Please register again.');
        }

        $otpRecord = Otp::where('identifier', $user->email)
                        ->where('otp', $request->otp)
                        ->where('type', 'registration')
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

            // Notify staff about pending approval
            $this->notifyStaffForApproval($healthCard);

            // Clear session
            session()->forget(['registration_otp', 'user_id']);

            // Login user
            Auth::login($user);

            return redirect()->route('user.dashboard')
                ->with('success', 'Account verified successfully! Your health card has been generated and is pending staff approval.');
        }

        return redirect()->route('login')
            ->with('error', 'Verification failed. Please try again.');
    }

    /**
     * Resend OTP for registration
     */
    public function resendOtp(Request $request)
    {
        if (!session('registration_otp')) {
            return redirect()->route('register');
        }

        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('register');
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Update existing OTP record
        Otp::where('identifier', $user->email)
            ->where('type', 'registration')
            ->update([
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false,
            ]);

        // Update session
        session(['registration_otp' => $otp]);

        return redirect()->back()
            ->with('success', 'New OTP sent to your email address. OTP: ' . $otp);
    }

    /**
     * Notify staff about pending health card approval
     */
    private function notifyStaffForApproval($healthCard)
    {
        // Get all staff users (you may need to adjust this based on your user roles)
        $staffUsers = User::where('user_type', 'staff')
            ->orWhere('user_type', 'admin')
            ->get();

        foreach ($staffUsers as $staff) {
            $staff->notifications()->create([
                'type' => 'health_card_pending_approval',
                'title' => 'New Health Card Pending Approval',
                'message' => "A new health card application from {$healthCard->user->name} ({$healthCard->card_number}) is pending your approval.",
                'data' => [
                    'health_card_id' => $healthCard->id,
                    'user_name' => $healthCard->user->name,
                    'card_number' => $healthCard->card_number,
                    'user_id' => $healthCard->user_id
                ]
            ]);
        }
    }
}
