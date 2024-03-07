<?php

namespace App\Models\Couple;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $table = "couples";

    protected $guarded = [];

    const STATUS_OUT_LOVE = 0;
    const STATUS_IN_LOVE = 1;

    // public function scopeInLove($query)
    // {
    //     return $query->where('status', self::STATUS_IN_LOVE);

    // }
    // public function scopeOutLove($query)
    // {
    //     return $query->where('status', self::STATUS_OUT_LOVE);
    // }
}
