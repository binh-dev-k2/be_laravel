<?php

namespace App\Services\Couple;

use App\Models\Couple\CoupleInvitation;

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

    public function checkInvitedUserBefore($fromUuid, $toUuid)
    {
        return !!CoupleInvitation::where('from_uuid', $fromUuid)
            ->where('to_uuid', $toUuid)
            ->where('status', CoupleInvitation::STATUS_PENDING)
            ->first();
    }
}
