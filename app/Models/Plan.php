<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
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
        'hd_images',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
