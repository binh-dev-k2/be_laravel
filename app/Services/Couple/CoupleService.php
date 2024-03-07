<?php

namespace App\Services\Couple;

use Illuminate\Support\Str;
use App\Models\Couple\Couple;
use Carbon\Carbon;

class CoupleService
{
    public function getCurrentCoupleByUser($user)
    {
        return Couple::where('sender_uuid', $user->uuid)
            ->orWhere('receiver_uuid', $user->uuid)
            ->where('status', Couple::STATUS_IN_LOVE)
            ->first();
    }

    public function getOldCoupleByUser($user)
    {
        return Couple::where('sender_uuid', $user->uuid)
            ->orWhere('receiver_uuid', $user->uuid)
            ->where('status', Couple::STATUS_OUT_LOVE)
            ->get();
    }

    public function getAllCoupleByUser($user)
    {
        return Couple::where('sender_uuid', $user->uuid)
            ->orWhere('receiver_uuid', $user->uuid)
            ->get();
    }

    public function isUserSingle($user)
    {
        $currentCouple = $this->getCurrentCoupleByUser($user);
        if ($currentCouple) {
            return false; // user duoc gui loi moi da co couple
        }
        return true;
    }

    public function createCouple($firstUserUuid, $secondUserUuid)
    {
        return Couple::create([
            'uuid' => Str::uuid(),
            'sender_uuid' => $firstUserUuid,
            'receiver_uuid' => $secondUserUuid,
            'status' => Couple::STATUS_IN_LOVE,
            'start_date' => Carbon::now()->format("Y-m-d H:i:s"),
            'saved_sender_uuid' => $firstUserUuid,
            'saved_receiver_uuid' => $secondUserUuid
        ]);
    }
}
