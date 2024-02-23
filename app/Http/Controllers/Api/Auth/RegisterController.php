<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Service\OTP\OTPService;
use App\Service\User\UserService;
use Carbon\Carbon;

class RegisterController extends Controller
{
    private $OTPService;
    private $userService;

    public function __construct(OTPService $OTPService, UserService $userService)
    {
        $this->OTPService = $OTPService;
        $this->userService = $userService;
    }

    public function register(AuthRequest $request)
    {
        $data = $request->validated();

        $user = $this->userService->findUserByEmail($data['email']);
        if (!$user) {
            $user = $this->userService->createUser($data);
            $this->OTPService->sendOTP($data['email']);
            return xmlResponse(0); // thanh cong
        }

        if (!$user->email_verified_at) {
            $otp = $this->OTPService->getLastestOTP($data['email']);
            if ($otp->submit == 0 && Carbon::parse($otp->expired_in)->isPast()) {
                $this->OTPService->sendOTP($data['email']);
                return xmlResponse(0);
            }
            return xmlResponse(3); // van con thoi gian cho otp
        }
        return xmlResponse(2); // loi da ton tai user
    }


    // verify user email
    public function verifyEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->OTPService->verifyOTP($data['email'], $data['code']);
        if ($verify !== 0) {
            return xmlResponse($verify); // loi theo code
        }

        $user = $this->userService->findUserByEmail($data['email']);
        $user->email_verified_at = Carbon::now()->getTimestamp();
        $user->save();
        return xmlResponse(0); // thanh cong
    }


    // resend email code
    public function resendVerificationEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->OTPService->getLastestOTP($data['email']);
        if ($verify->submit == 0 && Carbon::parse($verify->expired_in)->isPast()) {
            $this->OTPService->sendOTP($data['email']);
            return xmlResponse(0);
        }

        return xmlResponse(2); // otp da submit hoac van con thoi han
    }
}
