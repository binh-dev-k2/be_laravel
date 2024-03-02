<?php

namespace App\Models\Couple;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoupleTimeline extends Model
{
    use HasFactory;

    protected $table = "couple_timelines";

    protected $guarded = [];
}
