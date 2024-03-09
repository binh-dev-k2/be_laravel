<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Jobs\RenewPassword;
use App\Jobs\SendOTP;
use App\Services\OTP\OTPService;
use App\Services\User\UserService;

class ForgotPasswordController extends Controller
{
    protected $userService;
    protected $OTPService;

    public function __construct(UserService $userService, OTPService $OTPService)
    {
        $this->userService = $userService;
        $this->OTPService = $OTPService;
    }

    public function forgotPassword(AuthRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->findUserByEmail($data['email']);
        if (!$user) {
            return jsonResponse(2); //khong ton tai user
        }

        dispatch(new SendOTP($data['email']));
        return jsonResponse(0);
    }

    public function verifyOTPForgotPassword(AuthRequest $request)
    {
        $data = $request->validated();
        $verifyOTP = $this->OTPService->verifyOTP($data['email'], $data['code']);
        if ($verifyOTP != 0) {
            return jsonResponse($verifyOTP); //Loi theo code
        }

        dispatch(new RenewPassword($data['email']));
        return jsonResponse(0);
    }
}
