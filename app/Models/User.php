<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'profile_picture',
        'has_whatsapp',
        'has_viber',
        'is_email_visible',
        'facebook',
        'instagram',
        'youtube',
        'is_trader',
        'role',
        'email_verified_at',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_whatsapp' => 'boolean',
        'has_viber' => 'boolean',
        'is_email_visible' => 'boolean',
        'is_trader' => 'boolean',
    ];

    public function favorites()
    {
        return $this->belongsToMany(Vehicle::class, 'favorites', 'user_id', 'vehicle_id')->withTimestamps();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }
}
