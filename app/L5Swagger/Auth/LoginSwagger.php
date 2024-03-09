<?php

namespace App\L5Swagger\Auth;
use App\Http\Requests\Auth\AuthRequest;
use App\L5Swagger\L5Swagger;

class LoginSwagger
{
    /**
     * Đăng nhập tài khoản
     *
     * @OA\Post(
     *      path="/login",
     *      tags={"Auth"},
     *      summary="Đăng nhập tài khoản",
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
     *                  example={"email": "haitiger.al9@gmail.com", "password":"112233"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Tài khoản đã bị block",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>3</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Tài khoản chưa xác thực email",
     *         @OA\MediaType(
     *             mediaType="application/string",
     *             @OA\Schema(
     *                 type="string",
     *                 example="<response><data></data><code>4</code></response>"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Tài khoản hoặc mật khẩu không chính xác",
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
     *         description="Tài khoản và mật khẩu chính xác",
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
     public function login(AuthRequest $request){

     }
}
