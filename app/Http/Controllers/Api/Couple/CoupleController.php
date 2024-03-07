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

    public function getCurrentCouple()
    {
        $currentCouple = $this->coupleService->getCurrentCoupleByUser(Auth::user());
        if (!$currentCouple) {
            return jsonResponse(2); // dang doc than
        }
        return jsonResponse(0, $currentCouple);
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
}
