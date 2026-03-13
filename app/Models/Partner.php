<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'gallery',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'is_active',
        'show_opening_hours',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'show_opening_hours' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($partner) {
            if (empty($partner->slug)) {
                $partner->slug = Str::slug($partner->name);
            }
        });
    }

    public function services()
    {
        return $this->hasMany(PartnerService::class);
    }

    public function openingHours()
    {
        return $this->hasMany(PartnerOpeningHour::class);
    }

    public function reviews()
    {
        return $this->hasMany(PartnerReview::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->where('is_approved', true)->avg('rating'), 1) ?: 0;
    }
}
