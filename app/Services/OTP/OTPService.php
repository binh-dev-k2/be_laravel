<?php

namespace App\Services\OTP;

use App\Jobs\SendMail;
use App\Mail\VerifyEmail;
use App\Models\OTPUser;
use Carbon\Carbon;

class OTPService
{
    private $penddingOTP = 5; //minutes

    // Send a user code to email
    public function sendOTP($email)
    {
        $this->clearOldOTP($email);
        $code = $this->randomOTP();
        OTPUser::create([
            'email' => $email,
            'code' => $code,
            'expired_in' => Carbon::now()->addMinutes($this->penddingOTP),
        ]);

        dispatch(new SendMail($email, new VerifyEmail($code)));
    }

    // get lastest user otp
    public function getLatestOTP($email)
    {
        return OTPUser::where('email', $email)
            ->latest()
            ->first();
    }

    // Verify user code
    public function verifyOTP($email, $code)
    {
        $otp = $this->getLatestOTP($email);

        if (empty($otp)) {
            return 2;
        }
        if (Carbon::parse($otp->expired_in)->isPast()) {
            return 3;
        }
        if ($otp->code != $code) {
            return 4;
        }
        $this->submitOTP($otp);
        return 0;
    }

    public function randomOTP()
    {
        return rand(100000, 999999);
    }

    public function submitOTP($otp)
    {
        $otp->status = 1;
        $otp->save();
    }

    public function isOTPExpired($email)
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

    public function clearOldOTP($email)
    {
        return OTPUser::where('email', $email)->delete();
    }
}
