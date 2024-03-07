<?php

namespace App\Services\OTP;

use App\Jobs\SendOTP;
use App\Models\OTPUser;
use Carbon\Carbon;

class OTPService
{
    private $penddingOTP = 5; //minutes

    // Send a user code to email
    public function sendOTP($email)
    {
        dispatch(new SendOTP($email));
    }

    public function createOTPUser($email, $code)
    {
        return OTPUser::create([
            'email' => $email,
            'code' => $code,
            'expired_in' => Carbon::now()->addMinutes($this->penddingOTP),
        ]);
    }

    // get lastest user otp
    public function getLatestOTP($email)
    {
        return OTPUser::where('email', $email)->latest()->first();
    }

    // Verify user code
    public function verifyOTP($email, $code)
    {
        $otp = OTPUser::where('email', $email)
            ->where('code', $code)
            ->latest()
            ->first();

        if (empty($otp)) {
            return 2;
        }
        if (Carbon::parse($otp->expired_in)->isPast()) {
            return 3;
        }
        $this->submitOTP($otp);
        return 0;
    }

    public function generateOTP()
    {
        return rand(100000, 999999);
    }

    public function submitOTP($otp)
    {
        $otp->submit = 1;
        $otp->save();
    }

    public function isLatestOTPExpired($email)
    {
        $otp = $this->getLatestOTP($email);
        if (
            $otp
            && $otp->submit == 0
            && Carbon::parse($otp->expired_in)->isPast()
        ) {
            return false;
        }
        return true;
    }
}
