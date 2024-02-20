<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\AuthRequest;
use App\Jobs\SendMail;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $user = $this->createUser($data);
            $this->sendOTP($user->email);
            return xmlSuccessResponse($code = 0, $data = []);
        }

        if (!$user->email_verified_at) {
            $otp = $this->getLastestOTP($data['email']);
            if ($otp->submit == 0 && Carbon::parse($otp->expired_in)->isPast()) {
                $this->sendOTP($data['email']);
                return xmlSuccessResponse($code = 0, $data = []);
            }
        }
        return xmlErrorResponse(1, ['user da ton tai roi']);
    }


    // verify user email
    public function verifyEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->verifyOTP($data);
        if ($verify !== 0) {
            return xmlErrorResponse($code = $verify);
        }

        $user = User::where('email', $data['email'])->first();
        $user->email_verified_at = Carbon::now()->getTimestamp();
        $user->save();
        return xmlSuccessResponse(0);
    }


    // resend email code
    public function resendVerificationEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->getLastestOTP($data['email']);
        if ($verify->submit == 0 && Carbon::parse($verify->expired_in)->isPast()) {
            $this->sendOTP($data['email']);
            return xmlSuccessResponse($code = 0, $data = []);
        }

        return xmlErrorResponse($code = 1, $data = []);
    }


    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'uuid' => Str::uuid(),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
