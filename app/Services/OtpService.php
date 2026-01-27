<?php

namespace App\Services;

use App\Models\Service\OTP;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    /**
     * Create and store OTP
     * 
     * @param string identifier, purpose, module
     */
    public static function create(string $identifier, string $purpose, string $module)
    {
        // Generate OTP
        $plainOtp = random_int(100000, 999999);

        // Save
        Otp::create([
            'identifier' => $identifier,
            'purpose' => $purpose,
            'module' => $module,
            'otp' => Hash::make($plainOtp),
            'expires_at' => now()->addMinutes(1),
        ]);

        // Return
        return $plainOtp;
    }

    /**
     * Verify OTP
     * 
     * @param string identifier, purpose, inputOtp, module
     */
    public static function verify(string $identifier, string $purpose, string $inputOtp, string $module)
    {
        // Get latest record with identifier
        $verifyOtp = Otp::where([
            'identifier' => $identifier,
            'purpose' => $purpose,
            'module' => $module,
            'is_used' => false
        ])->latest()->first();
        
        // Check
        if (!$verifyOtp)
            return false;

        // Is expired
        if (now()->greaterThan($verifyOtp->expires_at))
            return false;

        // Verify OTP
        if (!Hash::check($inputOtp, $verifyOtp->otp))
            return false;

        // Update OTP
        $verifyOtp->update(['is_used' => true]);

        // Return
        return true;
    }
}