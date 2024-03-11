<?php

namespace App\Http\Controllers\Api\CoupleInvitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoupleInvitation\CoupleInvitationRequest;
use App\Services\CoupleInvitation\CoupleInvitationService;

class CoupleInvitationController extends Controller
{
    protected $coupleInvitationService;

    public function __construct(CoupleInvitationService $coupleInvitationService)
    {
        $this->coupleInvitationService = $coupleInvitationService;
    }

    public function listInvite()
    {
        $list = $this->coupleInvitationService->invitionListToMe();

        return jsonResponse(0, $list);
    }

    public function invite(CoupleInvitationRequest $request)
    {
        $data = $request->validated();
        $status = $this->coupleInvitationService->makeInvitation($data);

        return jsonResponse($status);
    }

    public function updateInvite(CoupleInvitationRequest $request)
    {
        $data = $request->validated();
        $status = $this->coupleInvitationService->updateInvitation($data);

        return jsonResponse($status);
    }
}
