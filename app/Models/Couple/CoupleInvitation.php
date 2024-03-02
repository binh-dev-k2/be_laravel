<?php

namespace App\Models\Couple;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoupleInvitation extends Model
{
    use HasFactory;

    protected $table = "couple_invitations";

    protected $guarded = [];

    const STATUS_PENDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_DENIED = 3;
}
