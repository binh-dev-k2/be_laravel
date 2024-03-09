<?php

namespace App\L5Swagger\Auth;
use App\Http\Requests\Auth\AuthRequest;
use App\L5Swagger\L5Swagger;

class RegisterSwagger
{
    /**
    * Kiểm tra Email
    *
    * @OA\Post(
    *      path="/check-mail",
    *      tags={"Auth"},
    *      summary="Kiểm tra xem email đã được đăng ký tài khoản chưa",
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
    *
    *                  example={"email": "haitiger.al9@gmail.com"}
    *              )
    *          )
    *      ),
    *    @OA\Response(
    *          response=200,
    *          description="Email chưa đăng ký",
    *          @OA\MediaType(
    *              mediaType="application/string",
    *              @OA\Schema(
    *                  type="string",
    *                 example="<response>
    <data></data>
    <code>0</code>
</response>"
    *              )
    *          )
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Email đã đăng ký",
    *          @OA\MediaType(
    *              mediaType="application/string",
    *              @OA\Schema(
    *                  type="string",
    *                  example="<response>
    <data></data>
    <code>2</code>
</response>"
    *              )
    *          )
    *      )
    * )
    */
    public function checkEmail(AuthRequest $request)
    {

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
   *              mediaType="application/string",
   *              @OA\Schema(
   *                  type="string",
   *                 example="<response>
  <data></data>
  <code>0</code>
</response>"
   *              )
   *          )
   *      ),
   *      @OA\Response(
   *          response=400,
   *          description="User đã tồn tại",
   *          @OA\MediaType(
   *              mediaType="application/string",
   *              @OA\Schema(
   *                  type="string",
   *                  example="<response>
  <data></data>
  <code>2</code>
</response>"
   *              )
   *          )
   *      ),
   *      @OA\Response(
   *          response=401,
   *          description="Vẫn còn thời gian chờ OTP, chuyển qua màn hình chờ OTP",
   *         @OA\MediaType(
   *              mediaType="application/string",
   *              @OA\Schema(
   *                  type="string",
   *                  example="<response>
  <data></data>
  <code>3</code>
</response>"
   *              )
   *          )
   *      )
   * )
   */
    public function register(AuthRequest $request)
    {

    }
    /**
     * Xác minh mã OTP
     *
     * @OA\Post(
     *      path="/email/verify",
     *      tags={"Auth"},
     *      summary="Xác minh mã OTP đúng không",
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
     *                      property="code",
     *                      type="string",
     *                      description="OTP code"
     *                  ),
     *                  example={"email": "haitiger.al9@gmail.com", "code":"112233"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Email không tồn tại OTP",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>2</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="OTP đã hết hạn",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>3</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="OTP không chính xác",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>4</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP chính xác",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>0</code></response>"
     *             )
     *         )
     *     )
     * )
     */
    public function verifyEmail(AuthRequest $request) {

    }
    /**
     * Gởi lại OTP
     *
     * @OA\Post(
     *      path="/email/resend-verification",
     *      summary="Yêu cầu gửi lại OTP",
     *      tags={"Auth"},
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
     *                  example={"email": "haitiger.al9@gmail.com"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="OTP đã submit hoặc không tồn tại",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>2</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đã gửi lại OTP về email",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>0</code></response>"
     *             )
     *         )
     *     )
     * )
     */
    public function resendVerificationEmail(AuthRequest $request) {

    }
}
