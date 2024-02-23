<?php

namespace App\Service\OTP;

use App\Jobs\SendMail;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OTPService
{
    private $db;
    private $penddingOTP = 5;

    public function __construct()
    {
        $this->db = DB::table('otp_users');
    }

    // Send a user code to email
    public function sendOTP($email)
    {
        $code = $this->randomOTP();
        $this->db
            ->insert([
                'email' => $email,
                'code' => $code,
                'expired_in' => Carbon::now()->addMinutes($this->penddingOTP),
            ]);

        dispatch(new SendMail($email, new VerifyEmail($code)));
    }

    // get lastest user otp
    public function getLastestOTP($email)
    {
        return $this->db
            ->where('email', $email)
            ->latest()
            ->first();
    }


    // Verify user code
    public function verifyOTP($email, $otp)
    {
        $verify = $this->getLastestOTP($email);

        if (empty($verify)) {
            return 2; // khong tim thay otp
        }
        if (Carbon::parse($verify->expired_in)->isPast()) {
            return 3; // otp qua han
        }
        if ($verify->code != $otp) {
            return 4; // otp khong khop voi ban ghi
        }
        return 0; // thanh cong
    }

    public function randomOTP()
    {
        return rand(100000, 999999);
    }

    public function submitOTP($email)
    {
        $this->db
            ->where('email', $email)
            ->update([
                'submit' => 1,
            ]);
    }
}
