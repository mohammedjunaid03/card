<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
            'aadhaar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Upload files
        $aadhaarPath = $request->file('aadhaar') ? $request->file('aadhaar')->store('private/aadhaar') : null;
        $photoPath = $request->file('photo') ? $request->file('photo')->store('public/photos') : null;

        // Calculate age
        $date_of_birth = Carbon::parse($request->date_of_birth);
        $age = $date_of_birth->age;

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
            'password' => $request->password, // Mutator handles bcrypt
            'aadhaar_path' => $aadhaarPath,
            'photo_path' => $photoPath,
            'status' => 'pending',
            'role' => 'user',
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

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful. Please verify your OTP to activate your account.')
            ->with('email', $user->email);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('register');
        }
        
        return view('auth.verify-otp');
    }

    /**
     * Handle OTP verification
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
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
            $user->status = 'active';
            $user->email_verified = true;
            $user->save();
        }

        return redirect()->route('login')
            ->with('success', 'OTP verified successfully. You can now login.');
    }
}
