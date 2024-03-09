<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\OTP\OTPService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $OTPService;
    protected $userService;

    public function __construct(OTPService $OTPService, UserService $userService)
    {
        $this->OTPService = $OTPService;
        $this->userService = $userService;
    }

    public function login(AuthRequest $request)
    {
        $data = $request->validated();
        $credentials = ['email' => $data['email'], 'password' => $data['password']];

        if (Auth::attempt($credentials)) {
            $user = $this->userService->findUserByEmail($data['email']);

            // check bloked user
            if ($user->hasBeenBlocked()) {
                return xmlResponse(3);
            }

            //check email verify
            if (!$user->hasVerifiedEmail()) {
                $isOTPExpired = $this->OTPService->isOTPExpired($data['email']);
                if ($isOTPExpired) {
                    $this->OTPService->sendOTP($data['email']);
                }
                return xmlResponse(4); // chua verify email
            }
            $response = [
                'token' => 'Bearer ' . $user->createToken('App')->accessToken
            ];
            return xmlResponse(0, $response);
        }

        return xmlResponse(2); // tai khoan hoac mat khau khong khop
    }
}
