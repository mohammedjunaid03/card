<?php

namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    public function generate($identifier, $type = 'registration')
    {
        // Generate random 6-digit numeric OTP
        $otp = rand(100000, 999999);
        
        // Delete old OTPs for this identifier
        Otp::where('identifier', $identifier)
           ->where('type', $type)
           ->delete();
        
        // Create new OTP
        $otpModel = Otp::create([
            'identifier' => $identifier,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => now()->addMinutes(config('app.otp_expiry_minutes', 10)),
        ]);
        
        return $otpModel;
    }
    
    public function verify($identifier, $otp, $type = 'registration')
    {
        $otpModel = Otp::where('identifier', $identifier)
                      ->where('otp', $otp)
                      ->where('type', $type)
                      ->where('is_used', false)
                      ->where('expires_at', '>', now())
                      ->first();
        
        if ($otpModel) {
            $otpModel->update(['is_used' => true]);
            return true;
        }
        
        return false;
    }
    
    public function sendOtpEmail($email, $otp)
    {
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your OTP for Health Card System');
        });
    }
    
    public function sendOtpSMS($mobile, $otp)
    {
        // Implement SMS gateway integration
        // Example: Twilio, MSG91, etc.
        // For now, we'll just log it
        \Log::info("OTP for {$mobile}: {$otp}");
    }
}