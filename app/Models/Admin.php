<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_picture',
        'gender',
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
        'password' => 'hashed',
    ];

    /**
     * The guard name for the model.
     *
     * @var string
     */
    protected $guard_name = 'admin-guard';

    /**
     * Get the avatar URL: uploaded photo or gender-based default.
     */
    public function getAvatarUrl(): string
    {
        if ($this->getAttribute('profile_picture')) {
            $path = $this->getAttribute('profile_picture');
            if (!str_starts_with($path, 'http') && !str_starts_with($path, 'storage/')) {
                return asset('storage/' . $path);
            }
            return asset($path);
        }
        // Male: 1.png  |  Female: 3.png
        $avatar = ($this->gender === 'female') ? '3.png' : '1.png';
        return asset('assets/img/avatars/' . $avatar);
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
