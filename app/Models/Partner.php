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
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
        'show_opening_hours' => 'boolean',
    ];

    protected $appends = ['average_rating', 'reviews_count'];

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
        // Try to use pre-calculated average from withAvg() if available
        if (array_key_exists('reviews_avg_rating', $this->attributes)) {
            $avg = $this->attributes['reviews_avg_rating'];
        } elseif ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->where('is_approved', true)->avg('rating');
        } else {
            $avg = $this->reviews()->where('is_approved', true)->avg('rating');
        }

        return round((float)($avg ?: 0), 1);
    }

    public function getReviewsCountAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->where('is_approved', true)->count();
        }
        return $this->reviews()->where('is_approved', true)->count();
    }
}
