<?php

namespace App\Models\Couple;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $table = "couples";

    protected $guarded = [];

    const STATUS_OUT_LOVE = 0;
    const STATUS_IN_LOVE = 1;

    public function sender() {
        return $this->belongsTo(User::class, 'sender_uuid', 'uuid');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_uuid', 'uuid');
    }

    // public function scopeInLove($query)
    // {
    //     return $query->where('status', self::STATUS_IN_LOVE);

    // }
    // public function scopeOutLove($query)
    // {
    //     return $query->where('status', self::STATUS_OUT_LOVE);
    // }
}
