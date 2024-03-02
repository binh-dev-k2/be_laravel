<?php

namespace App\Services\Couple;

use App\Models\Couple\CoupleInvitation;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoupleInvitationService
{
    protected $coupleService;
    protected $userService;

    public function __construct(CoupleService $coupleService, UserService $userService)
    {
        $this->coupleService = $coupleService;
        $this->userService = $userService;
    }

    public function makeInvitation($data)
    {
        $currentUser = Auth::user();
        $isCurrentUserSingle = $this->coupleService->isSingle($currentUser);
        if (!$isCurrentUserSingle) {
            return 2; // user khong doc than
        }

        $previosInvitation = $this->checkPreviousInvitation($currentUser->uuid);
        if ($previosInvitation) {
            return 3; // da co loi invite truoc do va van dang pending
        }

        $invitedUser = $this->userService->findUserByEmail($data['invited_email']);
        if (!$invitedUser) {
            return 4; // khong ton tai user duoc invite
        }

        $isInvitedUserSingle = $this->coupleService->isSingle($invitedUser);
        if (!$isInvitedUserSingle) {
            return 5; // user duoc gui loi moi da co couple
        }

        $this->createInvitation($currentUser->uuid, $invitedUser->uuid);
        return 0;
    }

    public function createInvitation($fromUuid, $toUuid)
    {
        return CoupleInvitation::create([
            'from_uuid' => $fromUuid,
            'to_uuid' => $toUuid,
            'status' => CoupleInvitation::STATUS_PENDING
        ]);
    }

    public function checkPreviousInvitation($userUuid)
    {
        return CoupleInvitation::where('from_uuid', $userUuid)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->first();
    }

    public function findInvitationById($id)
    {
        return CoupleInvitation::find($id);
    }

    public function updateInvitation($data)
    {
        $invitation = $this->findInvitationById($data['invitation_id']);
        if (!$invitation) {
            return 2; // khong tim thay id
        }
        try {
            switch ($data['status']) {
                case CoupleInvitation::STATUS_REJECTED:
                    return $this->rejectInvitation($invitation); // tu choi
                case CoupleInvitation::STATUS_DENIED:
                    return $this->deniedInvitation($invitation);
                case CoupleInvitation::STATUS_ACCEPTED:
                    return $this->acceptedInvitation($invitation);
            }
        } catch (Exception $e) {
            Log::error(date("Y-m-d H:i:s") . ": " . $e->getMessage());
            return 6; // Loi khong xac dinh
        }
    }

    public function rejectInvitation($invitation)
    {
        if (Auth::user()->uuid != $invitation->to_uuid) {
            return 3; // khong phai user duoc moi tu choi
        }
        $invitation->status = CoupleInvitation::STATUS_REJECTED;
        $invitation->save();
        if (Auth::user()->uuid != $invitation->from_uuid) {
            // notify($invitation->from_uuid);
        }
        return 0;
    }

    public function deniedInvitation($invitation)
    {
        if (Auth::user()->uuid != $invitation->from_uuid) {
            return 4;
        }
        $invitation->status = CoupleInvitation::STATUS_DENIED;
        $invitation->save();
        return 0;
    }

    public function acceptedInvitation($invitation)
    {
        if (Auth::user()->uuid != $invitation->to_uuid) {
            return 5; // khong phai user duoc moi dong y
        }
        $invitation->status = CoupleInvitation::STATUS_ACCEPTED;
        $invitation->save();

        $this->clearCoupleInvitation($invitation);
        $couple = $this->coupleService->createCouple($invitation->from_uuid, $invitation->to_uuid);
        $this->coupleService->createCoupleTimeline($couple);
        return 0;
    }

    public function clearCoupleInvitation($invitation)
    {
        CoupleInvitation::whereIn('from_uuid', [$invitation->from_uuid, $invitation->to_uuid])
            ->whereNotIn('id', (array)$invitation->id)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->update(['status' => CoupleInvitation::STATUS_REJECTED]);

        $fromInvites = CoupleInvitation::whereIn('to_uuid', [$invitation->from_uuid, $invitation->to_uuid])
            ->whereNotIn('id', (array)$invitation->id)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->get();

        $fromInvites->update(['status' => CoupleInvitation::STATUS_REJECTED]);
        // notify($fromInvites); lay from_uuid de gui notify
    }
}
