<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPUser extends Model
{
    use HasFactory;

    protected $table = "otp_users";

    protected $guarded = [];
}
