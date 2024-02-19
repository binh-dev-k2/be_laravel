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
    private $waitTimeUserCode = 5;

    public function register(AuthRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if ($user) {
            if (!$user->email_verified_at) {
                $verify = $this->getUserCode($data['email']);
                if ($verify->submit == 0 && Carbon::parse($verify->end_time)->isPast()) {
                    $this->sendUserCode($data['email']);
                    return xmlSuccessResponse($code = 0, $data = []);
                }
            }
            return xmlErrorResponse($code = 1, $data = ['user da ton tai roi']);
        }

        $user = User::create([
            'name' => $data['name'],
            'uuid' => Str::uuid(),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $this->sendUserCode($data['email']);

        return xmlSuccessResponse($code = 0, $data = []);
    }


    // verify user email
    public function verifyEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->verifyCode($data);
        if ($verify != 0) {
            return xmlErrorResponse($code = $verify, $data = []);
        }

        $user = User::where('email', $data['email'])->first();
        $user->email_verified_at = Carbon::now()->getTimestamp();
        $user->save();

        return xmlSuccessResponse($code = 0, $data = []);
    }


    // resend email code
    public function resendVerificationEmail(AuthRequest $request)
    {
        $data = $request->validated();

        $verify = $this->getUserCode($data['email']);
        if ($verify->submit == 0 && Carbon::parse($verify->end_time)->isPast()) {
            $this->sendUserCode($data['email']);
            return xmlSuccessResponse($code = 0, $data = []);
        }

        return xmlErrorResponse($code = 1, $data = []);
    }


    // login
    public function login(AuthRequest $request)
    {
        $data = $request->validated();

        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->email_verified_at) return xmlErrorResponse($code = 1, $data = []);

            $response = [
                'token' => 'Bearer ' . $user->createToken('App')->accessToken
            ];
            return xmlSuccessResponse(0, $response);
        }

        return xmlErrorResponse($code = 1, $data = []);
    }


    // Send a user code to email
    public function sendUserCode($email)
    {
        $code = rand(100000, 999999);

        DB::table('user_codes')
            ->insert([
                'email' => $email,
                'code' => $code,
                'end_time' => Carbon::now()->addMinutes($this->waitTimeUserCode),
            ]);

        dispatch(new SendMail($email, new VerifyEmail($code)));
    }


    public function getUserCode($email)
    {
        return DB::table('user_codes')
            ->where('email', $email)
            ->latest()
            ->first();
    }


    // Verify user code
    public function verifyCode($data)
    {
        $verify = $this->getUserCode($data['email']);

        if (empty($verify)) {
            return 1;
        }
        if (Carbon::parse($verify->end_time)->isPast()) {
            return 2;
        }
        if ($verify->code != $data['code']) {
            return 3;
        }

        DB::table('user_codes')
            ->where('email', $data['email'])
            ->update([
                'submit' => 1,
            ]);

        return 0;
    }
}
