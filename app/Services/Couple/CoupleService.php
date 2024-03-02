<?php

namespace App\Services\Couple;

use Illuminate\Support\Str;
use App\Models\Couple\Couple;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoupleService
{
    public function getUserCouples($invitedUser, $status = null)
    {
        $query = $invitedUser->couples();

        if ($status == Couple::STATUS_IN_LOVE) {
            $query->inLove();
        } elseif ($status == Couple::STATUS_OUT_LOVE) {
            $query->outLove();
        }

        $userCouples = $query->latest()->get();

        return $userCouples;
    }

    public function isSingle($user)
    {
        $inLoveUserCouple = $this->getUserCouples($user, Couple::STATUS_IN_LOVE);
        if (count($inLoveUserCouple) > 0) {
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
        return DB::table('couple_timelines')->insert([
            'couple_uuid' => $couple['uuid'],
            'start_date' => Carbon::now()->format("Y-m-d")
        ]);
    }
}
