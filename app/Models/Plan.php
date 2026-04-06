<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly',
        'price',
        'yearly_price',
        'billing_period',
        'features',
        'is_active',
        'color',
        'is_popular',
        'description',
        'active_ads_limit',
        'garage_ads_limit',
        'expandable_slots',
        'highlight_ads',
        'highlight_ad_count',
        'hd_images',
        'hd_images_count',
        'hd_images_normal_count',
        'hd_images_ad_count',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'hd_images_count' => 'integer',
        'hd_images_normal_count' => 'integer',
        'hd_images_ad_count' => 'integer',
        'highlight_ad_count' => 'integer',
        'price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
