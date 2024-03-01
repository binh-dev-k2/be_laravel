<?php

namespace App\Services\Couple;

use App\Models\Couple\CoupleInvitation;
use Illuminate\Support\Facades\Auth;

class CoupleInvitationService
{
    public function sendInvite($fromUuid, $toUuid)
    {
        return CoupleInvitation::create([
            'from_uuid' => $fromUuid,
            'to_uuid' => $toUuid,
            'status' => CoupleInvitation::STATUS_PENDING
        ]);
    }

    public function checkPreviousInvitation($userUuid)
    {
        return !!CoupleInvitation::where('from_uuid', $userUuid)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->first();
    }

    public function updateInvitation($userUuid, $status)
    {
        $currentUserUuid = Auth::user()->uuid;
        $invitation = CoupleInvitation::query()
            ->where('from_uuid', $currentUserUuid)
            ->where('status', CoupleInvitation::STATUS_PENDING);

        if ($currentUserUuid == $userUuid) {
            $invitation->first();
        } else {
            $invitation->where('to_uuid', $userUuid)->first();
        }
        if (!$invitation) {
            return false;
        }
        $invitation->update(['status' => $status]);
        if ($currentUserUuid == $userUuid) {
            // Gui thong bao cho user o day
        }
        return true;
    }
}
