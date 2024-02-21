<?php

namespace App\Service\User;

use App\Models\User;
use Illuminate\Support\Str;

class UserService
{

    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'uuid' => Str::uuid(),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => User::STATUS_ACTIVE
        ]);
    }
}
