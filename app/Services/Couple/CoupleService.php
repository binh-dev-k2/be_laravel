<?php

namespace App\Services\Couple;

use Illuminate\Support\Str;
use App\Models\Couple\Couple;
use App\Models\Couple\CoupleTimeline;
use Carbon\Carbon;

class CoupleService
{
    public function getInLoveCoupleByUser($user)
    {
        return Couple::where('first_user_uuid', $user->uuid)
            ->orWhere('second_user_uuid', $user->uuid)
            ->where('status', Couple::STATUS_IN_LOVE)
            ->first();
    }

    public function getOutLoveCoupleByUser($user)
    {
        return Couple::where('first_user_uuid', $user->uuid)
            ->orWhere('second_user_uuid', $user->uuid)
            ->where('status', Couple::STATUS_OUT_LOVE)
            ->get();
    }

    public function getListCoupleByUser($user)
    {
        return Couple::where('first_user_uuid', $user->uuid)
            ->orWhere('second_user_uuid', $user->uuid)
            ->get();
    }

    public function isSingle($user)
    {
        $inLoveUserCouple = $this->getInLoveCoupleByUser($user);
        if ($inLoveUserCouple) {
            return false; // user duoc gui loi moi da co couple
        }
        return true;
    }

    public function createCouple($firstUserUuid, $secondUserUuid)
    {
        return Couple::create([
            'uuid' => Str::uuid(),
            'first_user_uuid' => $firstUserUuid,
            'second_user_uuid' => $secondUserUuid,
            'status' => Couple::STATUS_IN_LOVE,
            'saved_first_user_uuid' => $firstUserUuid,
            'saved_second_user_uuid' => $secondUserUuid
        ]);
    }

    public function createCoupleTimeline($couple)
    {
        return CoupleTimeline::create([
            'couple_uuid' => $couple['uuid'],
            'start_date' => Carbon::now()->format("Y-m-d")
        ]);
    }

    public function getCurrentTimeline($couple)
    {
        return CoupleTimeline::where('couple_uuid', $couple->uuid)
            ->whereNull('end_date')
            ->first();
    }
}
