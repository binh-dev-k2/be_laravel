<?php

namespace App\Services\Couple;

use App\Http\Resources\Couple\CoupleResource;
use Illuminate\Support\Str;
use App\Models\Couple\Couple;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CoupleService
{
    public function getCurrentCoupleByUser($user)
    {
        $couple = Couple::where('sender_uuid', $user->uuid)
            ->orWhere('receiver_uuid', $user->uuid)
            ->where('status', Couple::STATUS_IN_LOVE)
            ->with(['sender', 'receiver'])
            ->first();

        return new CoupleResource($couple);
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

    public function updateCurrentCouple($data)
    {
        $currentUser = Auth::user();
        $currentCouple = $this->getCurrentCoupleByUser($currentUser);
        if (!$currentCouple) {
            return 2; // dang doc than
        }

        try {
            $status = $currentCouple->update([
                'status' => $data['status'],
                'start_time' => Carbon::parse($data['start_time'])->format("Y-m-d H:i:s"),
                'nickname' => $data['nickname'],
                'header_title' => $data['header_title']
            ]);

            if ($status) {
                return 0; // cập nhật thành công
            } else {
                return 3; // có lỗi xảy ra
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 3; //co loi xay ra
        }
    }

    public function createCouple($firstUserUuid, $secondUserUuid)
    {
        return Couple::create([
            'uuid' => Str::uuid(),
            'sender_uuid' => $firstUserUuid,
            'receiver_uuid' => $secondUserUuid,
            'status' => Couple::STATUS_IN_LOVE,
            'start_time' => Carbon::now()->format("Y-m-d H:i:s"),
            'saved_sender_uuid' => $firstUserUuid,
            'saved_receiver_uuid' => $secondUserUuid
        ]);
    }
}
