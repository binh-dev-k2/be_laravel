<?php

namespace App\Http\Controllers\Api\Couple;

use App\Http\Controllers\Controller;
use App\Http\Requests\Couple\CoupleRequest;
use App\Services\Couple\CoupleService;
use Illuminate\Support\Facades\Auth;

class CoupleController extends Controller
{
    protected $coupleService;

    public function __construct(CoupleService $coupleService)
    {
        $this->coupleService = $coupleService;
    }

    public function getCurrentCouple()
    {
        $currentCouple = $this->coupleService->getCurrentCoupleByUser(Auth::user());
        if (!$currentCouple) {
            return jsonResponse(2); // dang doc than
        }
        return jsonResponse(0, $currentCouple);
    }

    public function updateCouple(CoupleRequest $request)
    {
        $data = $request->validated();
        $currentCouple = $this->coupleService->updateCurrentCouple($data);
        if (!$currentCouple) {
            return jsonResponse(2); // dang doc than
        }

        return jsonResponse(0);
    }
}
