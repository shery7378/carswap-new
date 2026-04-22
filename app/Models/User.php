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
        'status',
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
        return $this->hasOne(Subscription::class)->where('status', 'active')->latestOfMany();
    }

    /**
     * Get the avatar URL: uploaded photo or UI Avatars default.
     */
    public function getAvatarUrl(): string
    {
        if ($this->getAttribute('profile_picture')) {
            // Ensure the path starts with storage/ for public access if not already present
            $path = $this->getAttribute('profile_picture');
            if (!str_starts_with($path, 'http') && !str_starts_with($path, 'storage/')) {
                return asset('storage/' . $path);
            }
            return asset($path);
        }
        $name = ($this->first_name || $this->last_name) ? $this->first_name . ' ' . $this->last_name : 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Accessor for profile_picture to ensure it returns a usable path/URL
     */
    public function getProfilePictureAttribute($value)
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http') || str_starts_with($value, 'storage/')) {
            return $value;
        }
        return 'storage/' . $value;
    }
}
