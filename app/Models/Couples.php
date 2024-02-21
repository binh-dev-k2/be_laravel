<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couples extends Model
{
    use HasFactory;

    const STATUS_OUT_LOVE = 0;
    const STATUS_IN_LOVE = 1;
}
