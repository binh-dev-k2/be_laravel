<?php

namespace App\Services\Couple;

use App\Models\Couple\Couple;

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
}
