<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $response = [
            'email' => $user['email'],
            'name' => $user['name'],
            'token' => 'Bearer ' . $user->createToken('App')->accessToken
        ];

        return successResponse('success', $response, 200);
    }

    public function login(AuthRequest $request)
    {
        $data = $request->validated();

        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $response = [
                'email' => $user['email'],
                'name' => $user['name'],
                'token' => 'Bearer ' . $user->createToken('App')->accessToken
            ];
            return successResponse('success', $response, 200);
        }

        return errorResponse('error', 'Unauthorised', 401);
    }
}
