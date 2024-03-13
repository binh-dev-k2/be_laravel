<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function index()
    {
        // $users = User::all();

        // return jsonResponse(
        //     0,
        //     new UserResource($users)
        // );
    }

    public function blockMySelf()
    {
        $result = $this->userService->setBlockUser(Auth::user());

        return jsonResponse($result);
    }
}
