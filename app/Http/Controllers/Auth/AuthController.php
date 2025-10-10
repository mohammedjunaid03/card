<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * User Registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'aadhaar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|max:1024',
        ]);

        // Custom validation: Check if same person already exists
        $existingUser = User::where('email', $request->email)
            ->where('mobile', $request->mobile)
            ->where('name', $request->name)
            ->where('date_of_birth', $request->dob)
            ->first();

        if ($existingUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'A user with the same name, email, phone number, and date of birth already exists.'
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload files
        $aadhaarPath = $request->file('aadhaar') ? $request->file('aadhaar')->store('private/aadhaar') : null;
        $photoPath = $request->file('photo') ? $request->file('photo')->store('public/photos') : null;

        // Calculate age
        $dob = Carbon::parse($request->dob);
        $age = $dob->age;

        // Create user
        $user = User::create([
            'name' => $request->name,
            'dob' => $request->dob,
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

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Verify OTP to activate account.',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp' => 'required|string',
        ]);

        $otpRecord = Otp::where('identifier', $request->identifier)
                        ->where('otp', $request->otp)
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        $otpRecord->update(['is_used' => true]);

        // Activate user
        $user = User::where('email', $request->identifier)->first();
        if ($user) {
            $user->status = 'active';
            $user->email_verified = true;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP verified. Account activated.',
        ]);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required', // email or mobile
            'password' => 'required|string',
        ]);

        $credentials = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ?
                        ['email' => $request->identifier, 'password' => $request->password] :
                        ['mobile' => $request->identifier, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account not active.'
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken; // For API

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid username or password.'
        ], 401);
    }

    /**
     * Logout (API + Web)
     */
    public function logout(Request $request)
    {
        // API logout
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Web logout
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Password Reset Request
     */
    public function passwordResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $otp = rand(100000, 999999);
        Otp::create([
            'identifier' => $request->email,
            'otp' => $otp,
            'type' => 'password_reset',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // TODO: Send OTP via email

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email for password reset.',
        ]);
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $otpRecord = Otp::where('identifier', $request->email)
                        ->where('otp', $request->otp)
                        ->where('is_used', false)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        $otpRecord->update(['is_used' => true]);

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password; // hashed via mutator
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successful.'
        ]);
    }
}
