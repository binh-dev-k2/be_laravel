<?php

namespace App\Models;

use App\Models\Couple\Couple;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = "users";

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'nick_name',
        'phone',
        'genter',
        'status',
        'avatar',
        'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function hasBeenBlocked()
    {
        return $this->status == self::STATUS_BLOCK;
    }

    public function couples()
    {
        return $this->hasMany(Couple::class, 'sender_uuid', 'uuid')
            ->orWhere('receiver_uuid', $this->uuid);
    }
}
