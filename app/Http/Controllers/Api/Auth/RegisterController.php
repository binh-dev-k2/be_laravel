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
    /**
     * Register User
     *
     * @OA\Post(
     *      path="/register",
     *      tags={"Auth"},
     *      summary="Register a new user or resend OTP",
     *      description="Registers a new user if the email is not already taken, or resends OTP if the user exists but is not verified.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="User's email address"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="User's name"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="User's password"
     *                  ),
     *                  @OA\Property(
     *                      property="password_confirmation",
     *                      type="string",
     *                      format="password",
     *                      description="Confirmation of user's password"
     *                  ),
     *                  example={"email": "haitiger.al9@gmail.com", "name": "admin", "password": "password", "password_confirmation": "password"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Schema(
     *                  type="string",
     *                  example="<xml>Success</xml>"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Schema(
     *                  type="string",
     *                  example="<xml>Error: user da ton tai roi</xml>"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function register(AuthRequest $request)
    {
        $data = $request->validated();

        $user = $this->userService->findUserByEmail($data['email']);
        if (!$user) {
            $user = $this->userService->createUser($data);
            $this->OTPService->sendOTP($data['email']);
            return xmlSuccessResponse(0);
        }

        if (!$user->email_verified_at) {
            $otp = $this->OTPService->getLastestOTP($data['email']);
            if ($otp->submit == 0 && Carbon::parse($otp->expired_in)->isPast()) {
                $this->OTPService->sendOTP($data['email']);
                return xmlSuccessResponse(0);
            }
            return xmlSuccessResponse(2);
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
        if ($verify->submit == 0 && Carbon::parse($verify->expired_in)->isPast()) {
            $this->OTPService->sendOTP($data['email']);
            return xmlSuccessResponse(0);
        }

        return xmlErrorResponse(1);
    }
}
