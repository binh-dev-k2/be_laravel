<?php

namespace App\Jobs;

use App\Mail\RenewPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RenewPassword implements ShouldQueue
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
    public function handle(UserService $userService)
    {
        try {
            $user = $userService->findUserByEmail($this->email);
            $password = Str::random(12);
            $user->password = Hash::make($password);
            $user->save();
            Log::info("Renew password: " . $this->email);
            Mail::to($user->email)->send(new RenewPasswordMail($password));
        } catch (\Exception $e) {
            Log::error(date("Y-m-d H:i:s") . "Renew password: " . $this->email . " Exception: " . $e->getMessage());
        }
    }
}
