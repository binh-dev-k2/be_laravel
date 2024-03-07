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
     *    @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Schema(
     *                   property="root",
     *                  type="string",
     *                  example="<xml>Success</xml>"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="User đã tồn tại",
     *          @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Schema(
     *                  type="string",
     *                  example="<xml>Success</xml>"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Vẫn còn thời gian chờ OTP, chuyển qua màn hình chờ OTP",
     *         @OA\MediaType(
     *              mediaType="application/xml",
     *              @OA\Schema(
     *                  type="string",
     *                  example=" <response>
                            <data></data>
                            <code>3</code>
                        </response>"
     *              )
     *          )
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
            return xmlResponse(0); // thanh cong
        }

        if (!$user->hasVerifiedEmail()) {
            $isLatestOTPExpired = $this->OTPService->isLatestOTPExpired($data['email']);
            if ($isLatestOTPExpired) {
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

        $isLatestOTPExpired = $this->OTPService->isLatestOTPExpired($data['email']);
        if ($isLatestOTPExpired) {
            $this->OTPService->sendOTP($data['email']);
            return xmlResponse(0);
        }

        return xmlResponse(2); // otp da submit hoac van con thoi han
    }
}
