<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OTPService;
use UserService;

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
            $this->OTPService->sendOTP($user->email);
            return xmlSuccessResponse(0);
        }

        if (!$user->email_verified_at) {
            $otp = $this->OTPService->getLastestOTP($data['email']);
            if ($otp->submit == 0 && Carbon::parse($otp->end_time)->isPast()) {
                $this->OTPService->sendOTP($data['email']);
                return xmlSuccessResponse(0);
            }
        }
        return xmlErrorResponse(1, ['user da ton tai roi']);
    }


    // verify user email
    public function verifyEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->OTPService->verifyOTP($data['email'], $data['code']);
        if ($verify !== 0) {
            return xmlErrorResponse($verify);
        }

        $user = $this->userService->findUserByEmail($data['email']);
        $user->email_verified_at = Carbon::now()->getTimestamp();
        $user->save();
        return xmlSuccessResponse(0);
    }


    // resend email code
    public function resendVerificationEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->OTPService->getLastestOTP($data['email']);
        if ($verify->submit == 0 && Carbon::parse($verify->end_time)->isPast()) {
            $this->OTPService->sendOTP($data['email']);
            return xmlSuccessResponse(0);
        }

        return xmlErrorResponse(1);
    }
}
