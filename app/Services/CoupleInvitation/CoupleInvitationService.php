<?php

namespace App\Services\CoupleInvitation;

use App\Models\CoupleInvitation\CoupleInvitation;
use App\Services\Couple\CoupleService;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
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

    public function invitionListToMe() {
        return CoupleInvitation::where('receiver_uuid', Auth::user()->uuid)->get();
    }

    public function makeInvitation($data)
    {
        try {
            $currentUser = Auth::user();
            $isCurrentUserSingle = $this->coupleService->isUserSingle($currentUser);
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

            $isInvitedUserSingle = $this->coupleService->isUserSingle($invitedUser);
            if (!$isInvitedUserSingle) {
                return 5; // user duoc gui loi moi da co couple
            }

            $this->createInvitation($currentUser->uuid, $invitedUser->uuid);
            return 0;
        } catch (Exception $e) {
            Log::error(date("Y-m-d H:i:s") . " Exception: " . $e->getMessage());
            return 6;
        }
    }

    public function createInvitation($senderUuid, $receiverUuid)
    {
        return CoupleInvitation::create([
            'sender_uuid' => $senderUuid,
            'receiver_uuid' => $receiverUuid,
            'status' => CoupleInvitation::STATUS_PENDING
        ]);
    }

    public function checkPreviousInvitation($userUuid)
    {
        return CoupleInvitation::where('sender_uuid', $userUuid)
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
                case CoupleInvitation::STATUS_ACCEPTED:
                    return $this->acceptedInvitation($invitation); // Chap nhan
                case CoupleInvitation::STATUS_REJECTED:
                    return $this->rejectInvitation($invitation); // tu choi
                case CoupleInvitation::STATUS_DENIED:
                    return $this->deniedInvitation($invitation); // Huy loi moi
                default:
                    return 7;
            }
        } catch (Exception $e) {
            Log::error(date("Y-m-d H:i:s") . ": " . $e->getMessage());
            return 7; // Loi khong xac dinh
        }
    }

    public function rejectInvitation($invitation)
    {
        if (Auth::user()->uuid != $invitation->receiver_uuid) {
            return 3; // khong phai user duoc moi tu choi
        }
        $invitation->status = CoupleInvitation::STATUS_REJECTED;
        $invitation->save();
        if (Auth::user()->uuid != $invitation->sender_uuid) {
            // notify($invitation->sender_uuid);
        }
        return 0;
    }

    public function deniedInvitation($invitation)
    {
        if (Auth::user()->uuid != $invitation->sender_uuid) {
            return 4; // khong phai user moi huy yeu cau
        }
        $invitation->status = CoupleInvitation::STATUS_DENIED;
        $invitation->save();
        return 0;
    }

    public function acceptedInvitation($invitation)
    {
        $currentUser = Auth::user();
        if ($currentUser->uuid != $invitation->receiver_uuid) {
            return 5; // khong phai user duoc moi dong y
        }
        if (!$this->coupleService->isUserSingle($currentUser)) {
            return 6;
        }
        $invitation->status = CoupleInvitation::STATUS_ACCEPTED;
        $invitation->save();

        $this->rejectPendingInvitation($invitation);
        $this->coupleService->createCouple($invitation->sender_uuid, $invitation->receiver_uuid);
        return 0;
    }

    public function rejectPendingInvitation($invitation)
    {
        CoupleInvitation::whereIn('sender_uuid', [$invitation->sender_uuid, $invitation->receiver_uuid])
            ->where('id', '!=', $invitation->id)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->update(['status' => CoupleInvitation::STATUS_REJECTED]);

        $receiverInvitations = CoupleInvitation::whereIn('receiver_uuid', [$invitation->sender_uuid, $invitation->receiver_uuid])
            ->where('id', '!=', $invitation->id)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->get();

        foreach ($receiverInvitations as $receiverInvitation) {
            $receiverInvitation->update(['status' => CoupleInvitation::STATUS_REJECTED]);
        }
        // notify($receiverInvitations); //lay sender_uuid de gui notify
    }
}
