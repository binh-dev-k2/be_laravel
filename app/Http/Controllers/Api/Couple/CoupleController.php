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
        $currentUser = Auth::user();

        $isCurrentUserSingle = $this->coupleService->isSingle($currentUser);
        if (!$isCurrentUserSingle) {
            return jsonResponse(2); // user khong doc than
        }

        $invitedUser = $this->userService->findUserByEmail($data['invited_email']);
        if (!$invitedUser) {
            return jsonResponse(3); // khong ton tai user duoc invite
        }

        $isInvitedUserSingle = $this->coupleService->isSingle($invitedUser);
        if (!$isInvitedUserSingle) {
            return jsonResponse(4); // user duoc gui loi moi da co couple
        }

        $check = $this->coupleInvitationService->checkInvitedUserBefore($currentUser->uuid, $invitedUser->uuid);
        if ($check) {
            return jsonResponse(5); // da co loi invite truoc do va van dang pending
        }

        $this->coupleInvitationService->sendInvite($currentUser->uuid, $invitedUser->uuid);
        return jsonResponse(0);
    }
}
