<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(AuthRequest $request)
    {
        $data = $request->validated();

        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->email_verified_at) return xmlErrorResponse(2);

            $response = [
                'token' => 'Bearer ' . $user->createToken('App')->accessToken
            ];
            return xmlSuccessResponse(0, $response);
        }

        return xmlErrorResponse(1, $data = []);
    }
}
