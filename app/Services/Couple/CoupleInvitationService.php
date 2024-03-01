<?php

namespace App\Services\Couple;

use App\Models\Couple\CoupleInvitation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoupleInvitationService
{
    protected $coupleService;

    public function __construct(CoupleService $coupleService)
    {
        $this->coupleService = $coupleService;
    }

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
        try {
            DB::beginTransaction();
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
            if ($status == CoupleInvitation::STATUS_ACCEPTED) {
                $couple = $this->coupleService->createCouple($currentUserUuid, $userUuid);
                DB::table('couple_timelines')->insert([
                    'couple_uuid' => $couple['uuid'],
                    'start_date' => Carbon::now()->format("Y-m-d")
                ]);
            }

            if ($currentUserUuid != $userUuid) {
                // Gui thong bao cho current user
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(date("Y-m-d H:i:s") . ": " . $e->getMessage());
            return false;
        }
    }
}
