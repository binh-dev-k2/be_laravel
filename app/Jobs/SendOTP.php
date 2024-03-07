<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use App\Services\OTP\OTPService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OTPService $OTPService,)
    {
        try {
            $isLatestOTPExpired = $OTPService->isLatestOTPExpired($this->email);
            if ($isLatestOTPExpired) {
                $code = $OTPService->generateOTP();
                $OTPService->createOTPUser($this->email, $code);
                Log::info("Mail to: " . $this->email);
                Mail::to($this->email)->send(new VerifyEmail($code));
            }
        } catch (Exception $e) {
            Log::error(date("Y-m-d H:i:s") . "Mail to: " . $this->email . " Exception: " . $e->getMessage());
        }
    }
}
