<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\OTP\OTPService;
use App\Services\User\UserService;
use Carbon\Carbon;

class RegisterController extends Controller
{
    protected $OTPService;
    protected $userService;

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

        if (!$user->hasVerifiedEmail()) {
            $checkLatestOTP = $this->OTPService->checkLatestOTP($data['email']);
            if (!$checkLatestOTP) {
                $this->OTPService->sendOTP($data['email']);
                return xmlResponse(0);
            }
            return xmlResponse(3); // van con thoi gian cho otp
        }
        return xmlResponse(2); // loi da ton tai user
    }


    public  function checkEmail(AuthRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->findUserByEmail($data['email']);

        if (!$user) {
            return xmlResponse(0);
        }
        return xmlResponse(2);
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
        return xmlResponse(0);
    }


    // resend email code
    public function resendVerificationEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $checkLatestOTP = $this->OTPService->checkLatestOTP($data['email']);
        if (!$checkLatestOTP) {
            $this->OTPService->sendOTP($data['email']);
            return xmlResponse(0);
        }

        return xmlResponse(2); // otp da submit hoac van con thoi han
    }
}
