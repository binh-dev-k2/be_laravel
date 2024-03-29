<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;
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

    public function setBlockUser($user)
    {
        try {
            if ($user->hasBeenBlocked()) {
                return 2; // user da bi block truoc do
            }

            $user->status = User::STATUS_BLOCK;
            $user->save();
            return 0;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return 4; // loi khong xac dinh
        }
    }

    public function setActiveUser($user)
    {
        try {
            if (!$user->hasBeenBlocked()) {
                return 2; // user chua bi block
            }

            $user->status = User::STATUS_ACTIVE;
            $user->save();
            return 0;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return 3; // loi khong xac dinh
        }
    }
}
