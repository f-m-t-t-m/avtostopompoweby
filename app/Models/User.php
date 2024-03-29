<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function department(): HasOne {
        return $this->hasOne(Department::class);
    }

    public function subjects(): HasMany {
        return $this->HasMany(Subject::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function notifications(): HasMany {
        return $this->hasMany(Notification::class);
    }

    public function student(): HasOne {
        return $this->hasOne(Student::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
