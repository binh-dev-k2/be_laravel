<?php

namespace App\Http\Controllers\Api\Couple;

use App\Http\Controllers\Controller;
use App\Http\Requests\Couple\CoupleRequest;
use App\Models\Couple\Couple;
use App\Services\Couple\CoupleInvitationService;
use App\Services\Couple\CoupleService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

class CoupleController extends Controller
{
    protected $coupleService;
    protected $userService;
    protected $coupleInvitationService;

    public function __construct(CoupleService $coupleService, UserService $userService, CoupleInvitationService $coupleInvitationService)
    {
        $this->coupleService = $coupleService;
        $this->userService = $userService;
        $this->coupleInvitationService = $coupleInvitationService;
    }

    public function invite(CoupleRequest $request)
    {
        $data = $request->validated();
        $status = $this->coupleInvitationService->makeInvitation($data);

        return jsonResponse($status);
    }

    public function updateInvite(CoupleRequest $request)
    {
        $data = $request->validated();
        $status = $this->coupleInvitationService->updateInvitation($data);

        return jsonResponse($status);
    }

    public function updateInvite(CoupleRequest $request)
    {
        $data = $request->validated();
        $isSuccessful = $this->coupleInvitationService->updateInvitation($data['user_uuid'], $data['status']);

        if ($isSuccessful) return jsonResponse(0);
        return jsonResponse(2); // Khong tim thay loi moi
    }
}
